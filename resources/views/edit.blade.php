@extends('layouts.app')

@section('content')
<!-- <form action="<?php echo Request::url(); ?>/edit/submit" name="myform" id="myform" method="post">
    {{ csrf_field() }}
    <div id="wysiwyg_cp" style="padding:8px; width:700px;">
    <input type="button" onClick="iBold()" value="B"> 
    <input type="button" onClick="iUnderline()" value="U">
    <input type="button" onClick="iItalic()" value="I">
    <input type="button" onClick="iUnorderedList()" value="UL">
    <input type="button" onClick="iOrderedList()" value="OL">
    </div>
    <textarea style="display:none;" name="myTextArea" id="myTextArea" cols="100" rows="14"></textarea>
    <iframe name="richTextField" id="richTextField" style="border:#000000 1px solid; width:700px; height:300px;"></iframe>
    <input name="myBtn" type="button" value="Submit Data" onClick="javascript:submit_form();"/>
</form> -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4">
            <h4>Materi: {{ $topic->name }}</h4>
            <div class="accordion" id="accordionCourses">
                <div class="card">
                    <div class="card-header" id="heading1" style="transform: rotate(0);">
                        <h5 class="mb-0">
                            <button class="btn stretched-link" type="button" data-toggle="collapse" data-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
                                Persoalan
                            </button>
                        </h5>
                    </div>
                    <div id="collapse1" class="collapse show" aria-labelledby="heading1" data-parent="#accordionCourses">
                        <form action="<?php echo Request::url(); ?>/save" name="myform" id="myform" method="post">
                            @csrf
                            <input type="hidden" value="<?php echo $id_spreadsheet; ?>" class="form-control" name="id_spreadsheet" id="id_spreadsheet">
                            <textarea name="richh" id="richh"><?php echo $topic->content;?></textarea>
                            <button style="float: right; margin-top: 1rem; margin-bottom: 1rem;" type="submit" class="btn btn-primary"><b>Simpan</b></button>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="heading2" style="transform: rotate(0);">
                        <h5 class="mb-0">
                            <button class="btn stretched-link" type="button" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                Kunci Jawaban
                            </button>
                        </h5>
                    </div>
                    <div id="collapse2" class="collapse" aria-labelledby="heading2" data-parent="#accordionCourses">
                        Iki isinie
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <iframe 
                style="width:100%; height:500px; border: 1px solid grey;" 
                frameBorder="0"
                src="https://docs.google.com/spreadsheets/d/<?php echo $id_spreadsheet; ?>/edit?usp=drivesdk&rm=embedded">
            </iframe>
        </div>
    </div>
</div>
@endsection