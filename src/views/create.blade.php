@extends('layouts.'.$template)
@section('content')

<div class="pt-50">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3>SEND NEWS LETTER</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif
                @if(session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif
            </div>
        </div>
        <form method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Recipients</label>
                        <select class="form-control select" placeholder="Recipients" name="recipients[]" multiple>
                            <option value="all" @if( old('recipients') ) @if(in_array('all', old('recipients'))) selected @endif @endif >All</option>
                            @foreach($types AS $type)
                            <option value="{{ $type['fldCode'] }}" @if( old('recipients') ) @if(in_array($type['fldCode'], old('recipients'))) selected @endif @endif>{{ $type['fldCodeDescription'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Select File</label>
                        <input type="file" class="form-control" placeholder="Image File" name="image">
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <input type="submit" value="Send Emails" class="btn btn-primary">
                </div>
            </div>
        </form>
    </div>
</div>
<script src="https://admirhodzic.github.io/multiselect-dropdown/multiselect-dropdown.js"></script>
@endsection