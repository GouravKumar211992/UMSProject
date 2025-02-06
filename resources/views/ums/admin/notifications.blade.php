@if($errors->any())
    <div class="alert alert-danger  p-2 alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <?php echo(str_replace('_' ,' ',$errors->first())) ?>
    </div>
@endif

@if (\Session::has('error'))
    <div class="alert alert-danger p-2 alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {!! \Session::get('error') !!}
    </div>
@endif

@if (\Session::has('success'))
    <div class="alert alert-success p-2 alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {!! \Session::get('success') !!}
    </div>
@endif

@if (\Session::has('message'))
    <div class="alert alert-success p-2 alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {!! \Session::get('message') !!}
    </div>
@endif

<?php if(\Session::has('error_msg')): ?>
    <div class="alert alert-danger p-2 alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {!! \Session::get('error_msg') !!}
    </div>
<?php endif; ?>
