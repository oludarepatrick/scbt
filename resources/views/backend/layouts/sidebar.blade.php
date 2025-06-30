<div class="wrapper">
            <div class="container" >
                <div class="row">
                    <div class="span3">
                        <div class="sidebar" >
                            <ul class="widget widget-menu unstyled">
                                <li class="active"><a href="{{url('/')}}"><i class="menu-icon icon-dashboard"></i>Dashboard
                                </a></li>
                                <li><a href="{{route('quiz.create')}}"><i class="menu-icon icon-bullhorn"></i>Create Quiz </a>
                                </li>
                                <li><a href="{{route('quiz.index')}}"><i class="menu-icon icon-inbox"></i>View Quiz <b class="label green pull-right">
                                    </b> </a></li>
                               
                            </ul>

                            <ul class="widget widget-menu unstyled">
                                <li><a href="{{route('question.create')}}"><i class="menu-icon icon-bullhorn"></i>Create Question </a>
                                </li>
                                <li><a href="{{route('question.index')}}"><i class="menu-icon icon-inbox"></i>View Question <b class="label green pull-right">
                                    </b> </a></li>
                               
                            </ul>

                            <ul class="widget widget-menu unstyled">
                                <li><a href="{{route('user.create')}}"><i class="menu-icon icon-bullhorn"></i>Create User </a>
                                </li>
                                <li><a href="{{route('user.index')}}"><i class="menu-icon icon-inbox"></i>View User <b class="label green pull-right">
                                    </b> </a></li>
                               
                            </ul>

                            <ul class="widget widget-menu unstyled">
                                <li>
                                    <a href="#" class="nav-link" data-toggle="collapse" data-target="#aiCbtDropdown" aria-expanded="false">
                                        <i class="menu-icon icon-lightbulb"></i> AI CBT <i class="icon-chevron-down pull-right"></i>
                                    </a>
                                    <ul id="aiCbtDropdown" class="collapse unstyled">
                                        <li>
                                            <a href="{{ route('teacher.curriculum.upload.form') }}">
                                                <i class="menu-icon icon-upload-alt"></i> Upload Curriculum
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('ai_questions.generate') }}">
                                                <i class="menu-icon icon-lightbulb"></i> Generate AI Questions
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('ai_questions.generate_maths') }}">
                                                <i class="menu-icon icon-lightbulb"></i> Generate Maths Questions
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('teacher.ai_questions') }}"><i class="icon-list"></i> AI Curriculum List</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('teacher.ai_questions_maths') }}"><i class="icon-list"></i> AI Maths Curriculum List</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>

                            <ul class="widget widget-menu unstyled">
                                <li><a href="{{route('user.exam')}}"><i class="menu-icon icon-bullhorn"></i>Assign Exam </a>
                                </li>
                                <li>
                                    <a href="{{route('re-assign')}}"><i class="menu-icon icon-inbox"></i>Re-Assign Exam <b class="label green pull-right"></b></a>
                                </li>
                               
                            </ul>

                            <ul class="widget widget-menu unstyled">
                                <li><a href="{{route('displayresult')}}"><i class="menu-icon icon-bullhorn"></i>View Result </a>
                                </li>
                               
                               
                            </ul>
                            <ul class="widget widget-menu unstyled">
                            <li>
                                <a href="{{ route('staffsubj.create') }}">
                                    <i class="menu-icon icon-book"></i> Add Subject
                                </a>
                            </li>
                        </ul>
                            <ul class="widget widget-menu unstyled">
                                <li>
                                    <a href="#" class="nav-link" data-toggle="collapse" data-target="#loginDetailsDropdown" aria-expanded="false">
                                        <i class="menu-icon icon-lock"></i> Login Details
                                    </a>
                                    <ul id="loginDetailsDropdown" class="collapse unstyled">
                                        <li><a href="{{ route('admin.login-details.staff') }}"><i class="menu-icon icon-user"></i> Staff</a></li>
                                        <li><a href="{{ route('admin.login-details.student') }}"><i class="menu-icon icon-user"></i> Student</a></li>
                                    </ul>
                                </li>
                            </ul>
                            <!--/.widget-nav-->
                            
                            
                           
                            <!--/.widget-nav-->
                            <ul class="widget widget-menu unstyled">
                                
                                <li>
                                    <a class="dropdown-item" href="{{ route('cbtLogout') }}">
                                        {{ __('Logout') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!--/.sidebar-->
                    </div>
                    <!--/.span3-->