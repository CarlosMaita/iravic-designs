@push('css')
    <link rel="stylesheet" href="{{asset("plugins/dropzone/dropzone.css")}}">
@endpush

@push('js')
    <script src="{{asset("plugins/dropzone/dropzone.js")}}"></script>

    <script>
        Dropzone.prototype.defaultOptions.dictDefaultMessage = "Arrastra los archivos aquí para subirlos";
        Dropzone.prototype.defaultOptions.dictFallbackMessage = "Su navegador no admite la carga de archivos mediante la función de arrastrar y soltar.";
        Dropzone.prototype.defaultOptions.dictFallbackText = "Utilice el formulario de respaldo a continuación para cargar sus archivos como en los viejos tiempos.";
        Dropzone.prototype.defaultOptions.dictFileTooBig = "El archivo es demasiado grande (0MiB). Máx .: 0MiB.";
        Dropzone.prototype.defaultOptions.dictInvalidFileType = "No puede cargar archivos de este tipo.";
        Dropzone.prototype.defaultOptions.dictResponseError = "El servidor respondió con el código statusCode.";
        Dropzone.prototype.defaultOptions.dictCancelUpload = "Cancelar carga";
        Dropzone.prototype.defaultOptions.dictCancelUploadConfirmation = "¿Estás seguro de que deseas cancelar esta carga?";
        Dropzone.prototype.defaultOptions.dictRemoveFile = "Remover archivo";
        Dropzone.prototype.defaultOptions.dictMaxFilesExceeded = "No puede cargar más archivos.";
    </script>
@endpush
