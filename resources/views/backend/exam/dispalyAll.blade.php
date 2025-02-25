<html>
    <head>
        <title>View Result</title>
        <style>
			table{ width:1000px; margin:0 auto;}
			table,table>tr,table>th,table>td{ border:black 1px solid; font-size:14px; padding:2px; border-collapse:collapse;}
			
			
            *{ font-family:Arial, Helvetica, sans-serif;}
			th,strong{ text-transform:uppercase;}
			
			@media print{
				#hide{
					display:none;
				}
			}
		</style>

    </head>
    <body>
        <?php 
            $i=0;
            echo "<div align='center'>
                    <button onClick='history.go(-1)'>go back</button>
                </div>";
            echo "<h3 align='center'>Quiz Title ".$quizTitle.", Class Name:".$class.", Arm: ".$arm."</h3>";
            echo "<table style='table-bordered table-collapsed:collapsed;' border='1' align='center' width='1000px'>";
            
            echo "<tr><th>S/N</th><th>Student ID</th><th>Student Name</th><th>Gender</th><th>Scores</th></tr>";
            foreach($students as $std)
            {
                $fdViewer=$std['quizId'].'-'.$std['userId'].'-'.$quizTitle."-".$std['name'];
                $myViwer=base64_encode($fdViewer);
                
                echo "<tr>";
                    echo "<td>".++$i."</td>";
                    echo "<td>".$std['student_id']."</td>";
                    echo "<td>".$std['name']."</td>";
                    echo "<td>".$std['sex']."</td>";
                
                    echo "<td align='center'>";
                        echo $std['userCorrectedAnswer']." Out of ".$std['totalQuestions']."<br>(".$std['percentage']." % )"; 
                        echo "<br/><a href='https://grafton.schooldriveng.com/stud_dashboard/dashboard/viewResult/".$myViwer."' class='btn btn-info btn-mini btn-sm' target='_blank'>view details</a>";
                    echo "</td>";
                echo "</tr>";
                
                
            }
            echo "</table>";
            echo "<div align='center'>
                <button onClick='history.go(-1)'>go back</button>
            </div>";
        ?>
    </body>
</html>