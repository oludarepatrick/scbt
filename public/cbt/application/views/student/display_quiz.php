<?php 
    $i=$startpoint+1;
    echo "<pre><div style='height:450px' class='divScroll'>";
    foreach($queses as $que)
    {
        echo "<div style='font-size: 15px !important; line-height:25px !important; text-align:justify' style='magin:margin-button:0px; padding-bottom:0px'>".$que['question']."</div><br/>";
        
        if(!empty($que['mfile_ext']))
        {     
            list($txt,$ext)=explode(".", $que['mfile_ext']);
            $ext=strtolower($ext);
                
            if($ext=="jpg" || $ext=="jpeg" ||  $ext=="png")
            {
                
                echo "<p><img src='".qLink.$que['mfile_ext']."' style='width:150px;height:120px'></p>";
            }
            elseif($ext=="mp3" || $ext=="mp4"){
                echo "<p>
                    <video width=\"140\" height=\"140\" controls>";
                    if($ext=="mp3"){
                        echo "<source src='".qLink.$que['mfile_ext']."' type='video/mp3'>";
                    }else{
                        echo "<source src='".qLink.$que['mfile_ext']."' type='video/mp4'>";
                    }
                    echo "</video>
                </p>";
            }
            elseif($ext=="pdf"){
                echo "<p>".qLink.$que['mfile_ext']."</p>";
            }
        }
        $myOption=1;
        
        echo "<div style='margin:0px; padding:0px'>Option(s)...<br/><br/></div>";
        echo "<div class='table-responsive'><table border='0' class='table-condensed table-collapse table-hover' cellpadding='0' cellspacing='0' style='border-collapse: collapse; width:90%'>";
        foreach($que['options'] as $optn)
        {
            $checked1=$optn['checken'];
            echo "<tr style='padding:0px !importanr; margin:3px !important;'>";
                echo "<td style='width:10%; padding:10px !important; margin:0px !important' align='center' valign='bottom'><input type=\"radio\" name=\"ans\" id='opt".$que['queId'].$myOption."' value='".$val.'-'.$que['queId'].'-'.$optn['answerId']."' class=\"optionsRadios1\" $checked1 /></td>";
                echo "<td style='padding:10px !important; margin:0px !important' valign='bottom'>&nbsp;<strong style='font-size:15px'>".$optn['option_desc']."</strong></td>";
            echo "</tr>";
            
            $myOption++;
        }
        echo "</table></div>";
        $i++;
    }
    echo "</div></pre>";
    
?>
<input type="hidden" class="dte" value="<?php echo $val; ?>">


<script type="text/javascript">

    $('.optionsRadios1').click(function()
    {
        var a = "#"+this.id;
        //alert(a);
        var c =$(a).val();
        //alert(c);
        $.post("<?php echo URL ?>dashboard/save?url=dashboard/save",{c:c},function(data){

            $(a).attr('value',data);

        });

    });

</script>

<script>
    $('.dbtn').click(function(){
        var kk = '<?php echo URL ?>dashboard/questions?url=dashboard/questions/'+$(this).attr('href');
        $('.val').load(kk); 
        //alert($(this).attr('href'));
        return false;
    });
</script>