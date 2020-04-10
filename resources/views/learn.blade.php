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
            <div class="card">
                <div class="card-header">Judul</div>
                <div class="card-body">
                    Ahaii
                </div>
            </div>
            <div>
                <form action="<?php echo Request::url(); ?>/edit/submit" method="post">
                    {{ csrf_field() }}
                    <mtextarea name=”myTextarea” id=”myTextarea”>Next, use our Get Started docs to setup Tiddny!</mtextarea>
                    <button type="submit" class="btn btn-primary"><b>Submit</b></button>
                </form>
            </div>
        </div>
        <div class="col-lg-8">
            <iframe 
                style="width:100%; height:500px; border: 1px solid grey;" 
                frameBorder="0"
                src="https://docs.google.com/spreadsheets/d/<?php echo $id_spreadsheet; ?>/edit?usp=drivesdk&rm=embedded">
            </iframe>
            <form action="<?php echo Request::url(); ?>/submit" method="post">
                <input type="hidden" value="<?php echo $id_spreadsheet; ?>" class="form-control" name="id_spreadsheet" id="id_spreadsheet">
                <button style="float: right;" type="submit" class="btn btn-success"><b>Submit</b></button>
            </form>
        </div>
    </div>
</div>
@endsection