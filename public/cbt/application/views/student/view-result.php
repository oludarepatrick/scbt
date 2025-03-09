<?php //exit(); ?>
<html>
    <head>
        <title>My Result</title>
        <link href="<?= URL?>public/css/bootstrap.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
        <?php 
            if(!empty($queses11))
            {
                echo "<div align='center'>
                    <button onClick='window.close()' class='btn btn-primary'>go back</button>
                    <h3 align:center>Student's Name: ".$std_name.", Quiz Title: ".$title."</h3>
                </div>";
                foreach($queses11 as $ques)
                {
                    echo "<table border='border='1' style='width:800px' class='table-striped table-bordered' align='center'>";
                        echo "<tr><td colspan='3'>".$ques['question']."</td></tr>";
                        echo "<tr><td>Option</td><td>Correct Option</td><td>Your Answer</td></tr>";
                        foreach($ques['options'] as $nn)
                        {
                            echo "<tr>
                                <td>".$nn['option_desc']."</td>"; 
                                if($nn['is_correct']==1){ echo "<td><i class=\"fa fa-check\" aria-hidden=\"true\"></i> Correct</td>"; }else{ echo "<td>&nbsp;</td>"; }
                                if(!empty($nn['checken']))
                                {
                                    if($nn['option_desc']==$nn['checken'] and $nn['is_correct']==1)
                                    {
                                        echo "<td style='border: 2px solid green; color:green;'><i class=\"fa fa-check\" aria-hidden=\"true\"></i> ".$nn['checken']."</td>";
                                    }else{
                                        echo "<td style='border: 2px solid red; color:red;'><i class=\"fa fa-remove\" aria-hidden=\"true\"></i> ".$nn['checken']."</td>";
                                    }
                                }else{
                                     echo "<td>".$nn['checken']."</td>";
                                }
                            echo "</tr>";
                        }
                    echo "</table><br/>";
                }
                echo "<div align='center'>
                    <button onClick='window.close()'>go back</button>
                </div>";
            }
        ?>
    </body>
</html>