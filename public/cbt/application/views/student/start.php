<div class="container">
    <style>
        #demo{
            background-color:azure !important; color:brown !important;
            -moz-box-shadow: 0 0 5px #888;
            -webkit-box-shadow: 0 0 5px#888;
            box-shadow: 0 0 5px #000000;
            font-weight:bolder;
            margin: 0 auto; 
            font-size:50px; 
            /*background-color: brown;*/ 
            color: #fff;
            padding: 20px;
            
            
        }
    </style>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span8 val" id="quiz"></div>
            <div class="span4">
                <input type="hidden" class="dte" value="<?php echo $didS; ?>">
                <!--Body content-->
                <div   id="sidebar">
                    <div align="center">
                    <img src="<?php echo isset($_SESSION['stud_pic'])?$_SESSION['stud_pic']:URL.'public/img/mas.png'; ?>" class="img-circle" allt="img" style="width:180px; height:180px; background-image: url(<?=URL?>public/img/mas.png)" />
                    </div>
                    <div style="font-family:Times New Roman; font-size:15px" class="slabel" align="center"><b><?php echo $_SESSION['logged_id']['fullname'];  ?></b></div>
                    <div style="font-family:Times New Roman;" class="slabel" align="center">(<?php echo $_SESSION['logged_id']['email'];  ?>)</div>
                    
                    <div style="font-family:Times New Roman; font-size:15px" class="slabel" align="center">
                        <strong>Title:<?php echo $quizAct->name;  ?></strong>
                        
                    </div>
                    
                    <div style="font-family:Times New Roman; font-size:17px;" class="slabel" align="center">
                        <strong>Time Allocated:<?php echo $quizTime;  ?> Minutes</strong>
                    </div>
                    
                    <br/>
                    
                    <p id="demo" class="slabel1" align="center" style=""></p>
                    <br/>
                    
                </div>
            </div>
        </div>

</div> <!-- /container -->
<script type="text/javascript">
    $(document).ready(function(e){ //alert('ok');
        var dtel =$('.dte').val();
        $('.val').html('loading Questions...<img src="<?php echo URL ?>public/images/loader.gif" width="32" height="32">');
        $('.val').load('<?php echo URL ?>dashboard/questions?url=dashboard/questions/'+dtel+'/1');
        
    });
</script>
<script type="text/javascript">
    window.oncontextmenu = function(){
        console.log("Right Click Disabled");
        return false;
    }
</script>

<!-- Display the countdown timer in an element -->
<script>
    var countDownDate = new Date().getTime();
    countDownDate=countDownDate + <?php echo $timerDetail->t_timer; ?>;
    // Update the count down every 1 second
    var x = setInterval(function() {

    // Get today's date and time
    var now = new Date().getTime();

    // Find the distance between now and the count down date
    var distance = countDownDate - now;

    // Time calculations for days, hours, minutes and seconds
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

    // Display the result in the element with id="demo"
    document.getElementById("demo").innerHTML =minutes + "m " + seconds + "s ";

    // If the count down is finished, write some text
    if (distance < 0) 
    {
        clearInterval(x);
        //document.getElementById("demo").innerHTML = "EXPIRED";
        location.href = "<?php echo URL?>dashboard/finish?url=dashboard/finish/<?php echo $timerDetail->id; ?>";
    }
    else{
        saveTime(<?php echo $timerDetail->id ?>);
    }
    }, 1000);


function saveTime(id)
{ 
   if(window.XMLHttpRequest){
		xmlhttp = new XMLHttpRequest();
	}else{
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");  
	}

	xmlhttp.onreadystatechange = function()
    {
		
		if(xmlhttp.readyState==4 && xmlhttp.status==200)
        {
			document.getElementById("report3nas1").innerHTML = xmlhttp.responseText;	
		}
		else{
			document.getElementById("report3nas1").innerHTML ="<img src='img/ajax-loader-8.gif'> <b>&nbsp;Please wait,Loading... </b>";
        }
	}
	xmlhttp.open("GET","<?php echo URL ?>dashboard/updateTimer?url=dashboard/updateTimer/"+id,true);
	xmlhttp.send();    
}	
</script>