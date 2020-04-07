@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Topik Materi</div>
                <div class="card-body">
                    <div class="accordion-group" id="accordionTopics">
                        @foreach($topics as $index => $topic)
                            <div class="card">
                                <div class="card-header" id="heading<?php echo $topic->id; ?>">
                                    <h6 class="mb-0">
                                        <a data-toggle="collapse" aria-expanded="false" aria-controls="collapse<?php echo $topic->id; ?>">
                                            <b>Topik {{ $index + 1 }}: {{ $topic->name }}</b>
                                        </a>
                                    </h6>
                                </div>
                                <div id="collapse<?php echo $topic->id; ?>" class="collapse show" aria-labelledby="heading<?php echo $topic->id; ?>" data-parent="#accordionTopics">
                                    <div class="card-body">
                                        {{ $topic->content }} <br/> <br/>
                                        <a href="<?php echo $topic->id_course; ?>/learn/<?php echo $topic->id_spreadsheet; ?>" class="btn btn-primary" role="button">Buka Topik</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div> <br/>
                    <a href="<?php echo $topic->id_course; ?>/learn/new" class="btn btn-primary" role="button">Tambah Topik</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Progress</div>
                <div class="card-body">
                    @foreach($topics as $index => $topic)
                        Topik {{ $index + 1 }}: 100/100 <br/>
                    @endforeach
                </div>
            </div>
            <br/>
            <div class="card">
                <div class="card-header">Peserta Kelas</div>
                <div class="card-body">
                    {{ $teacher }} (Pengajar) <br/>
                    @foreach($students as $student)
                        {{ $student }} <br/>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection