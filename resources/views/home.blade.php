@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<style>
 .content-page, body.sidebar-main .content-page {
    margin-left: 0;
    padding: 75px 0 0;
}

//extra 
  .card {
    position: relative;
    height: 150px;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    margin: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card h2 {
      text-align: center;
    }

    .ring-img {
      position: absolute;
      top: 18px;
      right: 28px;
      font-size: 4rem; 
    }

.box-shadow
{
	-webkit-box-shadow: 0 1px 1px rgba(72,78,85,.6);
	box-shadow: 0 1px 1px rgba(72,78,85,.6);
	-webkit-transition: all .2s ease-out;
	-moz-transition: all .2s ease-out;
	-ms-transition: all .2s ease-out;
	-o-transition: all .2s ease-out;
	transition: all .2s ease-out;
}

.box-shadow:hover
{
	-webkit-box-shadow: 0 20px 40px rgba(72,78,85,.6);
	box-shadow: 0 20px 40px rgba(72,78,85,.6);
	-webkit-transform: translateY(-15px);
	-moz-transform: translateY(-15px);
	-ms-transform: translateY(-15px);
	-o-transform: translateY(-15px);
	transform: translateY(-15px);
}

.wrap {
  width: 80%;
  margin: 0 auto;
}
/* Define colors for each task status */
.bar[data-count]:nth-of-type(1) { background: #f39c12; } /* Pending - Mild Orange */
.bar[data-count]:nth-of-type(2) { background: #3498db; } /* In Progress - Mild Blue */
.bar[data-count]:nth-of-type(3) { background: #2ecc71; } /* Completed - Mild Green */
.bar[data-count]:nth-of-type(4) { background: #e74c3c; } /* Cancelled - Mild Red */

 .bar {
 	background: #e74c3c;
 	width: 0;
 	margin: .25em 0;
 	color: #fff;
 	position: relative;
 	 transition:width 2s, background .2s;
   -webkit-transform: translate3d(0,0,0);
 	 clear: both;
 	 &:nth-of-type(2n) {
 	 	background:lighten( #e74c3c , 10% );
 	 }
 	 .label {
		font-size: .75em;
		padding: 1em;
		background: #3d3d3d;
		width: 8em;
		display: inline-block;
		position: relative;
		z-index: 2;
		font-weight: bold;
		font-family: 'Montserrat', sans-serif;
		 &.light {
 	 	background:lighten(#3d3d3d , 10% );
 	 }

}
 }
 .count {
	position: absolute;
	right: .25em;
	top: .75em;
 	padding: .15em; 
	font-size: .75em;
	font-weight: bold;
	font-family: 'Montserrat', sans-serif;
 }
 /* Responsive Adjustments */
 @media (max-width: 768px) {
    .ring-img {
        font-size: 2.5rem;
    }
    .wrap {
        width: 100%;
    }
    .bar {
        width: 100% !important;
        text-align: left;
    }
    .bar .label {
        font-size: 14px;
        width: auto;
        padding: 0.5em;
    }
    .count {
        font-size: 12px;
        top: 0.5em;
    }
}
/* Fade-in effect from left */
@keyframes fadeInLeft {
  from {
    opacity: 0;
    transform: translateX(-100px); /* Move from left */
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

/* Apply animation to h2 (Task Management Status) */
h2 {
  color: black;
  animation: fadeInLeft 1.5s ease-in-out;
}

/* Apply animation to Dashboard title */
h1 {
  animation: fadeInLeft 1.5s ease-in-out;
}

</style>
<div class="container">
  @if(auth()->user()->hasAnyRole('admin'))
    <div class="row d-flex justify-content-end flex-wrap">
        <div class="col-md-2 col-sm-6 col-12">
            <select id="user_id" class="form-control">
                <option value="">Select Assignee</option>
                <option value="all">All</option>
                @foreach($users as $user)
                    <option value="{{$user->id}}">{{$user->name}} ({{$user->getRoleNames()->first()}})</option>
                @endforeach 
            </select>
        </div>
    </div>
    @endif
    <div class="row mt-2">
    @if(!auth()->user()->hasAnyRole('employee'))
      <div class="col-md-4">
        <div class="card  box-shadow">
          <p><h3 style="margin-left: 20px;  color: black;" id="user-count-container">{{$usercount}}</h3> </p>
          <p style="margin-left: 20px; color: black;" >Total Employees</p>  
          <i class="icon-user ring-img"></i>
        </div>
      </div>
      @endif
      <div class="col-md-4">
        <div class="card  box-shadow">
          <p><h3 style="margin-left: 20px; color: black" id="task-count-container">{{$task}}</h3></p>
          <p style="margin-left: 20px; color: black;">Total Tasks</p>
          <i class="icon-book-open ring-img"></i>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card  box-shadow">
          <p><h3 style="margin-left: 20px; color: black" id="mytask-count-container">{{$mytask}}</h3></p>
          <p style="margin-left: 20px; color: black;">My Tasks</p>
          <i class="icon-pie-chart ring-img"></i>
        </div>
      </div>
    </div>
    <h2>Task Management Status</h2>
    <div class="wrap">
    <div class="holder mt-3">
        <div class="bar cf" 
             data-count="{{ $taskCounts['Pending'] ?? 0 }}">
            <span class="label">Pending</span>
        </div>
        <div class="bar cf" 
             data-count="{{ $taskCounts['In Progress'] ?? 0 }}">
            <span class="label light">In Progress</span>
        </div>
        <div class="bar cf" 
             data-count="{{ $taskCounts['Completed'] ?? 0 }}">
            <span class="label">Completed</span>
        </div>
        <div class="bar cf" 
             data-count="{{ $taskCounts['Cancelled'] ?? 0 }}">
            <span class="label light">Cancelled</span>
        </div>
    </div>
</div>


@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/css/bootstrap-extended.min.css">
<link rel="stylesheet" type="text/css" href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/fonts/simple-line-icons/style.min.css">
<link rel="stylesheet" type="text/css" href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/css/colors.min.css">
<link rel="stylesheet" type="text/css" href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/css/bootstrap.min.css">
<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
@stop

@section('js')
<script>
    function adjustBars() {
        let maxCount = {{ $maxCount }};
        $('.bar').each(function(i) {  
            var $bar = $(this);
            $(this).append('<span class="count"></span>');
            
            setTimeout(function() {
                let count = parseInt($bar.attr('data-count'));
                let width = maxCount > 0 ? (count / maxCount) * 100 : 0;
                
                if (width < 10 && count > 0) {
                    width = 10;
                }
                
                $bar.css('width', width + '%');
            }, i * 100);
        });

        $('.count').each(function () {
            $(this).prop('Counter', 0).animate({
                Counter: $(this).parent('.bar').attr('data-count')
            }, {
                duration: 2000,
                easing: 'swing',
                step: function (now) {
                    $(this).text(Math.ceil(now) + ' tasks');
                }
            });
        });
    }

    $(document).ready(function () {
        adjustBars();
        $(window).resize(adjustBars);
    });

    $(document).ready(function() {
        $('#user_id').change(function() {
            var userId = $(this).val();
            if (userId) {
                $.ajax({
                    url: "{{ route('filter.users.tasks') }}",
                    type: "GET",
                    data: { user_id: userId },
                    success: function(response) {
                        // Update Users List
                        $('#user-count-container').text(response.users.length);

                        // Update Task Counts
                        $('#task-count-container').text(response.task);
                        $('#mytask-count-container').text(response.mytask);

                        // Update Task Management Status bars
                        updateTaskBars(response.taskCounts);
                    }
                });
            }
        });

        function updateTaskBars(taskCounts) {
            let maxCount = Math.max(...Object.values(taskCounts), 1);
            
            $('.bar').each(function() {
                var $bar = $(this);
                var status = $bar.find('.label').text().trim();
                var count = taskCounts[status] || 0;
                
                let width = maxCount > 0 ? (count / maxCount) * 100 : 0;
                if (width < 10 && count > 0) width = 10;
                
                $bar.attr('data-count', count);
                $bar.css('width', width + '%');
                $bar.find('.count').text(count + ' tasks');
            });
        }
    });

</script>

@stop