<div class="card bg-verde-oscuro-600">
    <div class="card-header  text-white d-flex justify-content-between align-items-center">
        <div><i class="fa fa-bar-chart"></i>{{$title}} </div>
        <button id="btn-expand" class="btn btn-link text-white p-0 m-0" data-toggle="modal" data-target="#modal-expanded-{{$id}}"><i class="fa fa-expand"></i></button>
    </div>
    <div class="card-body bg-white">
        <div class="row">
            <div class="col-12">
                <canvas id="{{$id}}"></canvas>
            </div>
        </div>
    </div>
    <div id="modal-expanded-{{$id}}"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-expanded-{{$id}}" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content bg-white">
                <div class="modal-header">
                    <h5 class="modal-title text-verde-oscuro-600">{{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <canvas  id="{{$IdExpanded}}"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

