<template>
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Actualizar los parametro de inventario</h4>
      <button
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
      >
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
          <v-dropzone
            :ref="`dropzone-inventory`"
            :id="`dropzone-inventory`"
            :options="dropzoneOptions"
            @vdropzone-success="successImportDropzone"
            @vdropzone-sending="sendingDropzone"
            v-once
          ></v-dropzone>
          </div>
        </div>
      </div>
    </div>
    <br /><br />
    <div class="modal-footer">
      <div class="container-fluid">
        <div class="row">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            Cerrar
          </button>
          <button id="btn-importar" type="button" class="btn btn-primary" @click="importInventory">
            <!-- loading -->
            <i class="fas fa-spinner fa-pulse" v-if="loading"></i>
            Actualizar
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- /.modal-content -->
</template>

<script>

export default {
  components: {},
  props: {
    urlImport: {
      type: String,
      default: "",
    }
  },
  data() {
    return {
      loading: false,
      dropzoneOptions: {
        url: this.urlImport,
        autoProcessQueue: false,
        thumbnailWidth: 150,
        maxFilesize: 5,
        maxFiles: 1,
        dictDefaultMessage: "Arrastra los archivos aquí para subirlos",
        dictFallbackMessage:
          "Su navegador no admite la carga de archivos mediante la función de arrastrar y soltar.",
        dictFallbackText:
          "Utilice el formulario de respaldo a continuación para cargar sus archivos como en los viejos tiempos.",
        dictFileTooBig: "El archivo es demasiado grande (0MiB). Máx .: 0MiB.",
        dictInvalidFileType: "No puede cargar archivos de este tipo.",
        dictResponseError: "El servidor respondió con el código statusCode.",
        dictCancelUpload: "Cancelar carga",
        dictCancelUploadConfirmation:
          "¿Estás seguro de que deseas cancelar esta carga?",
        dictRemoveFile: "Remover archivo",
        dictMaxFilesExceeded: "No puede cargar más archivos.",
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('input[name="_token"]')
                .getAttribute("value"),
        }
      },
    };
  },
  mounted() {
    // console.log("Component mounted.");
  },
  methods: {
    importInventory() {
      this.$refs["dropzone-inventory"].processQueue();
    },
    successImportDropzone(file, response) {
        this.loading = false;
        this.cleanIntervalEachFiveSeconds();

        if (!response.success) {
            swal({
                type: "error",
                title: "Error",
                text: response.message,
            })
            this.$refs["dropzone-inventory"].removeFile(file);
        }else{
            swal({
                type: "success",
                title: "Éxito",
                text: response.message,
            })
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        }
    },
    sendingDropzone(file, xhr, formData) {
        this.loading = true;
    },
    cleanIntervalEachFiveSeconds() {
      const interval = setInterval(() => {
        clearInterval(interval);
      }, 5000);
    },

  },
};
</script>
