@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">Judul</div>
                <div class="card-body">
                    <?php echo $content;?>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <iframe 
                style="width:100%; height:500px; border: 1px solid grey;" 
                frameBorder="0"
                src="https://docs.google.com/spreadsheets/d/<?php echo $id_spreadsheet; ?>/edit?usp=drivesdk&rm=embedded">
            </iframe>
            <form action="<?php echo Request::url(); ?>/submit" name="myform" id="myform" method="post">
                @csrf
                <input type="hidden" value="<?php echo $id_spreadsheet; ?>" class="form-control" name="id_spreadsheet" id="id_spreadsheet">
                <button style="float: right;" type="submit" class="btn btn-success"><b>Submit</b></button>
            </form>
        </div>
    </div>
</div>
@endsection