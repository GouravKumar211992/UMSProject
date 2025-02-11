@if(( isset($errors) && $errors->any()) || Session::has('error') || isset($error) || Session::has('message') || isset($message))
<!-- messageBar -->
    <div id="messageBar" class="animated fadeInDown" style="margin: 0 20px;">
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" style="font-size: .5rem !important;">
                    <i class="glyph-icon fa fa-times fa-2x" style="cursor: pointer;"></i>
                </a>
                <div class="alert-content">
                    <!-- <h4 class="alert-title" style="margin: 5px;">
                        {{ trans('core.validation_error_title') }}
                    </h4> -->
                    <p>
                        <ul style="margin-left: 15px;">
                            @foreach($errors->all() as $error)
                                <li>{!! $error !!}</li>
                            @endforeach
                        </ul>
                    </p>
                </div>
            </div>
        @endif

        @if(isset($message) || Session::has('message'))
            <!-- status message -->
            @section('js')
                @parent
                <script>
                    $(document).ready(function() {
                        swal({
                            title: '',
                            text: {!! json_encode(isset($message) ? $message : Session::get('message')) !!},
                            type: 'message',
                            confirmButtonText: {!! json_encode(trans('core.ok')) !!}
                        });
                    });
                </script>
            @stop
        @endif
        
    </div>
    <!-- message bar ends -->
@endif

@if(isset($success) || Session::has('success'))
    @section('js')
        @parent
        <script>
            $(document).ready(function() {
                swal({
                    title: '',
                    text: {!! json_encode(isset($success) ? $success : Session::get('success')) !!},
                    type: 'success',
                    confirmButtonText: {!! json_encode(trans('core.ok')) !!}
                });
            });
        </script>
    @stop
@endif


@if(isset($quantityerror) || Session::has('quantityerror'))
    @section('js')
        @parent
        <script>
            $(document).ready(function() {
                swal({
                    title: '',
                    text: {!! json_encode(isset($quantityerror) ? $quantityerror : Session::get('quantityerror')) !!},
                    type: 'warning',
                })
                .then(() => {
                  window.location.href = '{{route("sale.index")}}';
                });
            });
        </script>
    @stop
@endif

@if(isset($warning) || Session::has('warning'))
    @section('js')
        @parent
        <script>
            $(document).ready(function() {
                swal({
                    title: '',
                    text: {!! json_encode(isset($warning) ? $warning : Session::get('warning')) !!},
                    type: 'warning',
                    confirmButtonText: {!! json_encode(trans('core.ok')) !!}
                });
            });
        </script>
    @stop
@endif

