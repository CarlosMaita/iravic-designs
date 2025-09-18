<template>
    <div v-if="isCurrencyModuleEnabled">
        <!-- Mobile Currency Button -->
        <button 
            type="button" 
            class="btn btn-icon btn-lg fs-xl btn-outline-secondary border-0 rounded-circle animate-shake d-lg-none"
            @click="showCurrencyModal = true"
            :title="'Moneda: ' + currentCurrency"
        >
            <i class="ci-dollar-sign animate-target"></i>
            <span class="visually-hidden">Cambiar moneda</span>
        </button>

        <!-- Desktop Currency Switcher (keep existing) -->
        <div class="d-none d-lg-flex align-items-center ms-3" data-currency-switcher>
            <span class="text-muted me-2 small">Moneda:</span>
            <div class="btn-group btn-group-sm" role="group" aria-label="Currency switcher">
                <input 
                    type="radio" 
                    class="btn-check" 
                    name="currency" 
                    id="currency-usd" 
                    value="USD" 
                    :checked="currentCurrency === 'USD'"
                    @change="changeCurrency('USD')"
                >
                <label class="btn btn-outline-secondary" for="currency-usd">USD</label>
                
                <input 
                    type="radio" 
                    class="btn-check" 
                    name="currency" 
                    id="currency-ves" 
                    value="VES"
                    :checked="currentCurrency === 'VES'"
                    @change="changeCurrency('VES')"
                >
                <label class="btn btn-outline-secondary" for="currency-ves">VES</label>
            </div>
        </div>

        <!-- Mobile Currency Modal -->
        <div 
            class="modal fade" 
            :class="{ 'show': showCurrencyModal }" 
            :style="{ display: showCurrencyModal ? 'block' : 'none' }"
            tabindex="-1" 
            aria-labelledby="currencyModalLabel" 
            :aria-hidden="!showCurrencyModal"
            @click="handleModalBackdropClick"
        >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" @click.stop>
                    <div class="modal-header">
                        <h5 class="modal-title" id="currencyModalLabel">
                            <i class="ci-dollar-sign me-2"></i>
                            Seleccionar Moneda
                        </h5>
                        <button 
                            type="button" 
                            class="btn-close" 
                            @click="showCurrencyModal = false"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small mb-3">
                            Elige la moneda para mostrar los precios:
                        </p>
                        <div class="d-grid gap-2">
                            <button 
                                type="button" 
                                class="btn btn-lg"
                                :class="currentCurrency === 'USD' ? 'btn-primary' : 'btn-outline-primary'"
                                @click="selectCurrency('USD')"
                            >
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <strong>USD - Dólar Americano</strong>
                                        <div class="small text-muted">Moneda base del catálogo</div>
                                    </div>
                                    <div class="fs-4">$</div>
                                </div>
                            </button>
                            
                            <button 
                                type="button" 
                                class="btn btn-lg"
                                :class="currentCurrency === 'VES' ? 'btn-primary' : 'btn-outline-primary'"
                                @click="selectCurrency('VES')"
                            >
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <strong>VES - Bolívar Venezolano</strong>
                                        <div class="small text-muted">
                                            Tasa: {{ exchangeRate ? formatNumber(exchangeRate) : 'Cargando...' }} Bs/$
                                        </div>
                                    </div>
                                    <div class="fs-4">Bs.</div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Backdrop -->
        <div 
            v-if="showCurrencyModal" 
            class="modal-backdrop fade show"
            @click="showCurrencyModal = false"
        ></div>
    </div>
</template>

<script>
export default {
    name: 'CurrencySwitcherComponent',
    data() {
        return {
            currentCurrency: 'USD',
            showCurrencyModal: false,
            exchangeRate: null,
            isCurrencyModuleEnabled: true // Will be updated from window.currencyData
        }
    },
    computed: {
        // Add any computed properties if needed
    },
    mounted() {
        this.loadCurrencyModuleStatus();
        this.loadStoredCurrency();
        this.loadExchangeRate();
        this.updatePricesOnLoad();
        
        // Listen for currency changes from other components
        window.addEventListener('currency-changed', this.handleCurrencyChange);
    },
    beforeDestroy() {
        window.removeEventListener('currency-changed', this.handleCurrencyChange);
    },
    methods: {
        loadCurrencyModuleStatus() {
            // Load from window.currencyData if available
            if (window.currencyData && typeof window.currencyData.enabled !== 'undefined') {
                this.isCurrencyModuleEnabled = window.currencyData.enabled;
            }
            
            // If module is disabled, force USD currency
            if (!this.isCurrencyModuleEnabled) {
                this.currentCurrency = 'USD';
                localStorage.setItem('selectedCurrency', 'USD');
            }
        },
        
        loadStoredCurrency() {
            // Don't load stored currency if module is disabled
            if (!this.isCurrencyModuleEnabled) {
                this.currentCurrency = 'USD';
                return;
            }
            
            const stored = localStorage.getItem('selectedCurrency');
            if (stored && ['USD', 'VES'].includes(stored)) {
                this.currentCurrency = stored;
            }
        },
        
        loadExchangeRate() {
            // Try to get from window global first (faster)
            if (window.currencyData && window.currencyData.exchangeRate) {
                this.exchangeRate = window.currencyData.exchangeRate;
                return;
            }
            
            // Fallback to API call
            fetch('/api/exchange-rate')
                .then(response => response.json())
                .then(data => {
                    this.exchangeRate = data.rate;
                })
                .catch(error => {
                    console.error('Error loading exchange rate:', error);
                    this.exchangeRate = 365; // fallback rate
                });
        },
        
        changeCurrency(currency) {
            // Prevent changing to VES if module is disabled
            if (!this.isCurrencyModuleEnabled && currency === 'VES') {
                return;
            }
            
            if (currency !== this.currentCurrency) {
                this.currentCurrency = currency;
                this.saveCurrency();
                this.updatePrices();
                this.emitCurrencyChange();
            }
        },
        
        selectCurrency(currency) {
            this.changeCurrency(currency);
            this.showCurrencyModal = false;
        },
        
        saveCurrency() {
            localStorage.setItem('selectedCurrency', this.currentCurrency);
        },
        
        updatePrices() {
            // Update all price elements on the page
            this.updatePriceElements();
            
            // Emit event for other components
            this.$emit('currency-changed', this.currentCurrency);
        },
        
        updatePricesOnLoad() {
            // Initial price update on component load
            this.$nextTick(() => {
                this.updatePriceElements();
            });
        },
        
        updatePriceElements() {
            const priceElements = document.querySelectorAll('[data-usd-price]');
            
            priceElements.forEach(element => {
                const usdPrice = parseFloat(element.getAttribute('data-usd-price'));
                if (!isNaN(usdPrice)) {
                    if (this.currentCurrency === 'VES' && this.exchangeRate) {
                        const vesPrice = usdPrice * this.exchangeRate;
                        element.textContent = 'Bs. ' + this.formatNumber(vesPrice);
                    } else {
                        element.textContent = '$' + this.formatNumber(usdPrice);
                    }
                }
            });
        },
        
        emitCurrencyChange() {
            // Emit custom event for other parts of the application
            const event = new CustomEvent('currency-changed', {
                detail: {
                    currency: this.currentCurrency,
                    exchangeRate: this.exchangeRate
                }
            });
            window.dispatchEvent(event);
        },
        
        handleCurrencyChange(event) {
            if (event.detail && event.detail.currency !== this.currentCurrency) {
                this.currentCurrency = event.detail.currency;
            }
        },
        
        handleModalBackdropClick(event) {
            if (event.target === event.currentTarget) {
                this.showCurrencyModal = false;
            }
        },
        
        formatNumber(number) {
            return new Intl.NumberFormat('es-VE', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(number);
        }
    },
    watch: {
        showCurrencyModal(newVal) {
            if (newVal) {
                document.body.classList.add('modal-open');
            } else {
                document.body.classList.remove('modal-open');
            }
        }
    }
}
</script>

<style scoped>
.modal.show {
    background-color: rgba(0, 0, 0, 0.5);
}

.btn:hover {
    transform: translateY(-1px);
    transition: transform 0.2s ease;
}

.btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.btn-outline-primary:hover {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
</style>