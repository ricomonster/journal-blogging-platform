@extends('installer.layout')
@section('title', 'System Requirements')

@section('css')
<style type="text/css">

</style>
@endsection

@section('content')
<div id="installer_requirements">
    <header class="subheader">
        <h3>Oops!</h3>
    </header>
    <section class="content">
        <p>
            It seems you haven't installed Journal properly. Make sure that your
            system installed the proper extensions and set the correct folder
            permissions.
        </p>

        <p>
            You can check below the things that you need to fix in order to run
            Journal and once you're finished complying to the requirements,
            just refresh this page in order to install Journal.
        </p>

        <br/>
        <h4>Extensions</h4>
        <table class="table table-bordered">
            <thead>
                <th width="50px">&nbsp;</th>
                <th>Extension</th>
            </thead>
            <tbody>
                @foreach($server['requirements'] as $extension => $installed)
                <tr>
                    <td class="text-center">
                        @if($installed)
                        <i class="fa fa-check-circle"></i>
                        @else
                        <i class="fa fa-times-circle"></i>
                        @endif
                    </td>
                    <td>{{$extension}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <br/>

        <h4>Folder Permissions</h4>

        <table class="table table-bordered">
            <thead>
                <th width="50px">&nbsp;</th>
                <th>Folder</th>
                <th>Current</th>
                <th>Expected</th>
            </thead>
            <tbody>
                @foreach($folders['folders'] as $folder)
                <tr>
                    <td class="text-center">
                        @if($folder['set'])
                        <i class="fa fa-check-circle"></i>
                        @else
                        <i class="fa fa-times-circle"></i>
                        @endif
                    </td>
                    <td>
                        <code>{{$folder['folder']}}</code>
                    </td>
                    <td>
                        {{$folder['current']}}
                    </td>
                    <td>
                        {{$folder['expected']}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</div>
@endsection
