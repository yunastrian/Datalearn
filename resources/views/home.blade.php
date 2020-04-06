@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Kelasku</div>
                <div class="card-columns card-body">
                    @foreach($enrolled as $enroll)
                        <div class="card course" style="width: 14rem">
                            <img src="img/course1.png" class="card-img-top" alt="No Picture">
                            <div class="card-header">{{ $enroll->name }}</div>
                            <a href="/class/<?php echo $enroll->id; ?>" class="stretched-link"></a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Profil</div>
                <div class="card-body profile">
                    <img src="img/profile.jpg" style="width:120px;height:120px;" alt="No Picture"> <br/> <br/>
                    <a id="name">{{ $profile->name }}</a> <br/>
                    <a id="email">{{ $profile->email }}</a> <br/>
                    <a id="role">{{ $role }}</a> <br/>
                    <a id="edit" href="/edit_profile" class="btn btn-primary" role="button">Edit Profil</a>
                </div>
            </div>
        </div>
    </div>
    <div class="py-4 row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Kelas Tersedia</div>
                <div class="card-body">
                    <div class="accordion" id="accordionCourses">
                        @foreach($courses as $index => $course)
                            <div class="card">
                                <div class="card-header" id="heading<?php echo $course->id; ?>">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link stretched-link" type="button" data-toggle="collapse" data-target="#collapse<?php echo $course->id; ?>" aria-expanded="false" aria-controls="collapse<?php echo $course->id; ?>">
                                            {{ $course->name }}
                                        </button>
                                    </h5>
                                </div>

                                <div id="collapse<?php echo $course->id; ?>" class="collapse" aria-labelledby="heading<?php echo $course->id; ?>" data-parent="#accordionCourses">
                                    <div class="card-body">
                                        {{ $course->description }} <br/>
                                        Pengajar : {{ $teachers[$index] }} <br/> <br/>
                                        <a href="/enroll" class="btn btn-primary" role="button">Daftar Kelas</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    courses {
        display: block;
        width: 100%;
        height: 100%;
    }
</style>