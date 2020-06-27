@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-primary text-white"><b>{{ $topic_name }}</b></div>
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
            <a href="/course/<?php echo $id_course; ?>" class="btn btn-primary" id="back" role="button">Kembali ke Kelas</a>
            <button id="submit" style="float: right;" type="text" onclick="submit('<?php echo $id_spreadsheet; ?>', '<?php echo Request::url(); ?>/submit')" class="btn btn-success"><b>Submit</b></button>
            <button id="loading" style="float: right; display: none;" type="button" class="btn btn-success" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><b> menilai...</b></button>
        </div>
        <div  id="result" class="col-lg-12" style="margin-top: 1rem; display:none">
            <div class="card">
                <div class="card-header bg-primary text-white">Hasil</div>
                <div id="result-detail" class="card-body"></div>
            </div>
        </div>
    </div>
</div>
@endsection