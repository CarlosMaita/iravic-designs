@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    
    {{-- 
    <script>
        var options = {
            group: 'share',
            animation: 100
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
                console.log({
                    'event': name,
                    'this': this,
                    'item': evt.item,
                    'from': evt.from,
                    'to': evt.to,
                    'oldIndex': evt.oldIndex,
                    'newIndex': evt.newIndex
                });
            };
        });
    </script>
     --}}
@endpush

