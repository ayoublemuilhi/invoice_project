@if ($errors->any())
    <div class="alert alert-danger my-2" role="alert" style=" color: #b54b55;background-color: #f8d7da;border-color: #f5f2f2;">
        <ul style="font-size: 14px;list-style: circle">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(Session::has('success'))
    <div class="alert-success success" id="alert-message">
        <span class="closebtn">{{Session::get('success')}}</span>
    </div>
@endif

@if(Session::has('error'))
    <div class="alert-error error" style="margin: 22px;" id="alert-message">
        <span class="closebtn">{{Session::get('error')}}</span>
    </div>
@endif
