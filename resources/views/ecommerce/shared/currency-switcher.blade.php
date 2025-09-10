<!-- Currency Switcher Component -->
<div class="currency-switcher" data-currency-switcher>
    <div class="d-flex align-items-center">
        <span class="text-muted me-2">Moneda:</span>
        <div class="btn-group" role="group" aria-label="Currency switcher">
            <input type="radio" class="btn-check" name="currency" id="currency-usd" value="USD" checked>
            <label class="btn btn-outline-primary btn-sm" for="currency-usd">USD ($)</label>
            
            <input type="radio" class="btn-check" name="currency" id="currency-ves" value="VES">
            <label class="btn btn-outline-primary btn-sm" for="currency-ves">VES (Bs.)</label>
        </div>
        
        <div class="ms-2 text-muted" data-exchange-rate-info style="font-size: 0.8em;">
            <small>1 USD = <span data-rate>{{ number_format($exchangeRate ?? 36.50, 2, ',', '.') }}</span> VES</small>
        </div>
    </div>
</div>

<!-- Currency Switcher Styles -->
<style>
.currency-switcher {
    margin-bottom: 1rem;
}

.currency-switcher .btn-check:checked + .btn-outline-primary {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
    color: white;
}

.price-display {
    transition: opacity 0.2s ease;
}

.price-display.switching {
    opacity: 0.6;
}

@media (max-width: 768px) {
    .currency-switcher {
        text-align: center;
    }
    
    .currency-switcher .d-flex {
        flex-direction: column;
        align-items: center !important;
        gap: 0.5rem;
    }
    
    .currency-switcher [data-exchange-rate-info] {
        margin-left: 0 !important;
    }
}
</style>

<!-- Currency Switcher JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Currency switcher functionality
    const currencySwitcher = document.querySelector('[data-currency-switcher]');
    if (!currencySwitcher) return;
    
    // Currency data from backend
    const currencyData = {!! \App\Helpers\CurrencyHelper::getJavascriptData() !!};
    
    // Current selected currency
    let currentCurrency = 'USD';
    
    // Get all price elements
    function getPriceElements() {
        return document.querySelectorAll('[data-price], [data-price-usd]');
    }
    
    // Format price according to currency
    function formatPrice(amount, currency) {
        const decimals = currencyData.decimals[currency] || 2;
        const symbol = currencyData.symbols[currency] || '';
        
        if (currency === 'VES') {
            return symbol + ' ' + amount.toLocaleString('es-VE', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            });
        } else {
            return symbol + amount.toLocaleString('en-US', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            });
        }
    }
    
    // Convert price between currencies
    function convertPrice(amount, fromCurrency, toCurrency) {
        if (fromCurrency === toCurrency) return amount;
        
        if (fromCurrency === 'USD' && toCurrency === 'VES') {
            return amount * currencyData.exchangeRate;
        } else if (fromCurrency === 'VES' && toCurrency === 'USD') {
            return amount / currencyData.exchangeRate;
        }
        
        return amount;
    }
    
    // Update all prices on the page
    function updatePrices(newCurrency) {
        const priceElements = getPriceElements();
        
        priceElements.forEach(element => {
            // Add switching animation
            element.classList.add('switching');
            
            // Get original USD price
            const originalUsdPrice = parseFloat(element.dataset.priceUsd || element.dataset.price || 0);
            
            // Convert to target currency
            const convertedPrice = convertPrice(originalUsdPrice, 'USD', newCurrency);
            
            // Format and update display
            setTimeout(() => {
                element.textContent = formatPrice(convertedPrice, newCurrency);
                element.classList.remove('switching');
            }, 100);
        });
        
        currentCurrency = newCurrency;
        
        // Store preference in localStorage
        localStorage.setItem('preferred_currency', newCurrency);
        
        // Update exchange rate info visibility
        const rateInfo = currencySwitcher.querySelector('[data-exchange-rate-info]');
        if (rateInfo) {
            rateInfo.style.display = newCurrency === 'VES' ? 'block' : 'block';
        }
    }
    
    // Handle currency change
    currencySwitcher.addEventListener('change', function(e) {
        if (e.target.name === 'currency') {
            updatePrices(e.target.value);
        }
    });
    
    // Load saved preference
    const savedCurrency = localStorage.getItem('preferred_currency');
    if (savedCurrency && ['USD', 'VES'].includes(savedCurrency)) {
        const radioButton = currencySwitcher.querySelector(`input[value="${savedCurrency}"]`);
        if (radioButton) {
            radioButton.checked = true;
            updatePrices(savedCurrency);
        }
    }
    
    // Update exchange rate from API periodically
    function updateExchangeRate() {
        fetch('/api/currency/exchange-rate')
            .then(response => response.json())
            .then(data => {
                currencyData.exchangeRate = data.rate;
                const rateDisplay = currencySwitcher.querySelector('[data-rate]');
                if (rateDisplay) {
                    rateDisplay.textContent = data.rate.toLocaleString('es-VE', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 4
                    });
                }
                
                // Re-convert prices if currently showing VES
                if (currentCurrency === 'VES') {
                    updatePrices('VES');
                }
            })
            .catch(error => console.log('Error updating exchange rate:', error));
    }
    
    // Update rate every 5 minutes
    setInterval(updateExchangeRate, 300000);
});
</script>