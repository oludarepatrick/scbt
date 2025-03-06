<div class="container">

    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span8 divScroll">
                <!--Sidebar content-->
                
                <?php
                    echo "<h4 align='center'>".strtoupper($active_term)." ".$active_session." ACADEMIC SESSION</h4>";
                    if(!empty($isExamAssigned))
                    {
                        
                        echo "<div class='table-responsive'><table border='1' class='table-striped table-borderd table-hover table-collepsed table-condensed' style='max-width:1000px'>";
                        echo "<tr>
                            <th>S/N</th>
                            <th>Quiz Title</th>
                            <th>Subject</th>
                            <th>Class Name</th>
                            <th>Total Question</th>
                            <th>Time</th>
                            <th>Score/Action</th>
                        </tr>";
                        $is=0;
                        foreach($assignedQuiz as $dd)
                        {
                            $ggdf=$dd['quizId'].'-'.$studId.'-'.$dd['remeningTime'];
                            $qIdStdAtm=base64_encode($ggdf);
                            
                            $fdViewer=$dd['quizId'].'-'.$studId.'-'.$dd['quizName']."-".$_SESSION['logged_id']['fullname'];
                            $myViwer=base64_encode($fdViewer);
                            
                            
                            echo "<tr>";
                                echo "<td>".++$is."</td>";
                                echo "<td>".$dd['quizName']."</td>";
                           
                                echo "<td>".$dd['subject_id']."</td>";
                                echo "<td>".$dd['class_id']." ".$dd['arm']."</td>";
                                echo "<td>".$dd['totalQue']."</td>";
                           
                                echo "<td>".$dd['minutes']." Minutes</td>";
                            
                                $dds1=strtolower(substr($dd['subject_id'], 0,4));
                                
                                $dds2=strtolower(substr($dd['class_id'], 0,6));
                                
                                if($dds1=='biol'){
                                    //echo $ggdf;
                                }
                                
                            
                            
                            if($dd['remeningTime'] > 0 and $dd['time_status']==0)
                            {
                                
                                $dsdCont=($dd['isAtempt']==0?'Start':'Continue');
                                //if( ($dds1=='biol' and ($dds2=='sss 1' || $dds2=='sss 2')) || ($dds1=='math' and $studId=='459')  )
                                //if(($dds1=='furt' || $dds1=='biol' and ($dds2=='sss 2' || $dds2=='sss 1') ) || (($dds2=='jss 1' || $dds2=='jss 2') and ($dds1=='phe' || $dds1=='igbo' || $dds1=='comp' || $dds1=='yoru' || $dds1=='basi') ) || ($dds1=='math' and $studId=='459')  )
                                //if(( $dds1=='oral' || $dds1=='anim' || $dds1=='dyei' || $dds1=='math' || $dds1=='cate' || $dds1=='engl' || $dds1='furt' and ($dds2=='sss 1') ) || (($dds2=='jss 1' || $dds2=='jss 2') and ($dds1=='engl') ) || ($dds2=='sss 2')  )
                                if($dds2=='sss 1' || $dds2=='sss 2' || $dds2=='jss 1' || $dds2=='jss 2')
                                {
                                    //echo "<td colspan='2'><a href='".URL."dashboard/start/".$qIdStdAtm."' class='btn btn-success btn-sm' >".$dsdCont."</a></td>";
                                    echo "<td colspan='2'><a href='#' class='btn btn-sm btn-danger' disabled>not available</a></td>";
                                }
                                else{
                                    //echo "<td colspan='2'><a href='#' class='btn btn-sm btn-danger' disabled>not available</a></td>";
                                    
                                    echo "<td colspan='2'><a href='".URL."dashboard/start/".$qIdStdAtm."' class='btn btn-success btn-sm' >".$dsdCont."</a></td>";
                                }
                                
                            }
                            else{
                                echo "<td align='center'>";
                                    echo $dd['mark']." Marks out of ".$dd['totalQue']." (".$dd['totalScores']."%)";
                                    //echo "<br/><a href='".URL."dashboard/viewResult/".$myViwer."' class='btn btn-info btn-mini btn-sm' target='_blank'>view details</a>";
                                echo "</td>";
                            }
                            echo "</tr>";
                            
                            
                        }
                        echo "</table></div>";
                    }
                    else{
                        echo "<pre><h4 style='color:red'>No Available Question(s) for now. Check back later</h4></pre>";
                    } 
                ?>
                
                   
            </div>
            <div class="span4">
                <!--Body content-->
                <div   id="sidebar">
                    <div align="center">
                        <img src="<?php echo isset($_SESSION['stud_pic'])?$_SESSION['stud_pic']:URL.'public/img/mas.png'; ?>" class="img-circle" allt="img" style="width:180px; height:180px; background-image: url(<?=URL?>public/img/mas.png)" />
                    </div>
                    <div style="" class="slabel" align="center"><b><?php echo $_SESSION['logged_id']['fullname'];  ?></b></div>
                    <div style="" class="slabel" align="center"><b>(<?php echo $_SESSION['logged_id']['email'];  ?>)</b></div>
                    
                    <br/>
                </div>
            </div>
        </div>

</div> <!-- /container -->
<script type="text/javascript">
    $('.pr').click(function(){

        var a = "#"+this.id; var b = ".v"+this.id;
        var c =$(b).val();

        if(confirm("Are you sure you want start?\n"+c)){
            return true;
        }else{
            return false;
        }});
</script>

