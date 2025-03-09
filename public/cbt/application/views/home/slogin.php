<h2><legend>Student Login</legend></h2>
<div style="color: blue; font-weight: bold">
    Enter your registration number below to login
</div><br/>
<form class="form-inline"  action="<?= URL ?>home/login" method="post" data-validate="parsley" id="new">

    <div class="control-group <?php echo !empty($regError)?'error':'';?>">
        <label class="control-label"><i class="icon-asterisk"></i>&nbsp;<b>Registration Number:</b></label>
        <div class="controls">
            <input type="text" name="reg" placeholder="Registration Number Is Required" value="<?php echo (!empty($_POST['reg'])?$_POST['reg']:''); ?>" required />
            <?php if (!empty($regError)): ?>
                <span class="help-inline"><?php echo $regError;?></span>
            <?php endif;?>
        </div>
    </div>
    <div class="">
        <button type="submit" class="btn btn-mini btn-success" name="login">login</button>
        <button type="reset" class="btn btn-mini">cancel</button>&nbsp;&nbsp;

    </div>
</form>