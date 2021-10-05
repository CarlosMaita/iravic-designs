<script>
    function SortableJs(id_container, handle) {
        this.id_container = id_container;
        this.handle = handle;
        this.sortable = null;
    }

    /**
     *  
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

    SortableJs.prototype.destroyElement = function() {
        this.sortable.destroy();
    }
</script>