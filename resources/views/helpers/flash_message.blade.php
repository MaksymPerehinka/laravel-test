@if(session()->has('flash_message') && session()->has('flash_css_class'))
<div class="alert alert-dismissible {{ session()->get('flash_css_class') }}">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {!!  session()->get('flash_message') !!}
</div>
@endif