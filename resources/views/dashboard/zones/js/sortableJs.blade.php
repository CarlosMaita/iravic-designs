<script>
    function SortableJs(id_container, handle) {
        this.id_container = id_container;
        this.handle = handle;
        this.sortable = null;
    }

    /**
     *  Crea el componente para ordenar
     *  Maneja eventos para detectar cambios de posicion para cada item
    */
    SortableJs.prototype.create = function(callback) {
        let options = {
            group: 'share',
            animation: 100,
            handle: this.handle
        };

        events = [
            'onChoose',
            'onStart',
            'onEnd',
            'onAdd',
            'onUpdate',
            'onSort',
            'onRemove',
            'onChange',
            'onUnchoose'
        ].forEach(function (name) {
            options[name] = function (evt) {
                if (name == 'onEnd' && evt.oldIndex != evt.newIndex) {
                    callback();
                }
            };
        });

        let container = document.getElementById(this.id_container);
        this.sortable = Sortable.create(container, options);
    }

    /**
     * El componente sortable
     * Se elimina para volver a crear el mismo, ya que da conflicto para actualizarlo una vez creado
     */
    SortableJs.prototype.destroyElement = function() {
        this.sortable.destroy();
    }
</script>