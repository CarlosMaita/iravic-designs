@push('css')
    <link rel="stylesheet" href="{{asset('css/cropit.css')}}">

<style>
      .cropit-preview {
        background-color: #f8f8f8;
        background-size: cover;
        border: 1px solid #ccc;
        border-radius: 3px;
        margin-top: 7px;
        width: 250px;
        height: 250px;
      }

      .cropit-preview-image-container {
        cursor: move;
      }

      .image-size-label {
        margin-top: 10px;
      }

   

      #result {
        margin-top: 10px;
        width: 900px;
      }

      #result-data {
        display: block;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        word-wrap: break-word;
      }
      /* Translucent background image */
      .cropit-preview-background {
        opacity: .2;
      }

      /*
       * If the slider or anything else is covered by the background image,
       * use relative or absolute position on it
       */
      input.cropit-image-zoom-input {
        position: relative;
      }

      /* Limit the background image by adding overflow: hidden */
      /*#image-cropper {
        overflow: hidden;
      }*/
    </style>
@endpush

@push('js')
<script src="{{asset('js/jquery.cropit.js')}}"></script>
<script>
  $(function(){
    // $('#image-cropper').cropit({ imageBackground: true });
  })
</script>
@endpush