<div id="dialog" class="card" title="Neuer Eintrag in die Karte">
    <div class="card-header">Ihr Wunsch oder Anregung
        <span id="hint"></span>
        <span id="close" type="button" class="close right text-danger">
        <i class="fa fa-window-close"></i>
        </span>
    </div>

    <div class="card-body">
        <form id="newobjectform"  enctype="multipart/form-data" action="#" method="post" >
                       
<!-- Username  ----------------------------------   --> 

<label for="username"><strong>Ihr Name oder Pseudonym:</strong></label>
<input type="text" name="username" maxlength="16" id="username" placeholder="Name" class="text" required="required">
<br>

<!-- Userinfo  ----------------------------------   --> 

<?php if ($boolUserinfo):  ?>                  
    <label><strong>Die Fragen nach Alter und Verkehrsmittel können Sie freiwillig beantworten:</strong></label>
    <br>
    <label for="ext_age">Ihr Alter:</label>
        <select name="ext_age" id="ext_age">
<?php 
        foreach ($arrAge as $age) {
                echo " <option value='$age'>$age</option>";
        }   
?>      
       </select>      
    <br>               
                    
    <label for="ext_transport">Ihr hauptsächlich genutztes Verkehrsmittel:</label>
    <select name="ext_transport" id="ext_transport">
<?php
        foreach ($arrTransport as $transport) {
            echo " <option value='$transport'>$transport</option>";
        }             
?>                   
    </select>
<br>
<?php endif; ?>

<!-- Beschreibung  ----------------------------------   --> 

<label for="description"><strong>Beschreibung:</strong></label><br>
<textarea name="description" id="description" maxlength="1001" placeholder="Beschreiben Sie Ihren Eintrag" required="required"></textarea>

<!-- Themenfelder  ----------------------------------   -->                

<strong>Wählen Sie ein Themenfeld aus:</strong></br>
<div class="row">
<?php
    $first=true;
    foreach ($arrTopic as $keyTopic => $valTopic):
        $checked = ($first) ? "checked=\"checked\"" : "";
        $first=false;
?>
    <div class="col-6-md col-6">
        <input type="radio" id="topic<?=$keyTopic?>" name="topic" value="<?=$keyTopic?>" <?=$checked?>/>
        <label for="topic<?=$keyTopic?>"><?=$valTopic?></label>
    </div>

<?php   endforeach;      ?>                      
</div> <!-- row -->

<!-- Mängelkategorie  --------------------------------   --> 

<?php if ($boolDefect): ?>
<p>
    <label for="defect"><strong>Mängelkategorie:</strong></label>
        <select name="defect" id="defect">
<?php
        foreach ($arrDefect as $defectKey => $defectVal) {
            echo " <option value='$defectKey'>$defectVal</option>";
        }             
?>                   
        </select>
</p>

<?php endif; ?>

<!-- Lokalisierung  ----------------------------------   -->    

<input type="hidden" id="lng" name="lng" value="0" />
<input type="hidden" id="lat" name="lat" value="0" />

<!-- Dateiupload  ----------------------------------   -->   

<?php if ($boolUpload): ?>            
                    <label>Bildupload:</label>
                    <input type="file" id="photo" name="uploadfile" size="60" maxlength="255" />
<?php endif; ?> 

<!-- Einverständnis  ----------------------------------   --> 
  
<strong>Einverständnis:</strong><br>

<label for="consent">
<input type="checkbox" id="consent" name="consent" value="1" required="required" />
      Ich bin einverstanden, dass die von mir eingegebenen Daten in der 
        Karte veröffentlicht 
        <?=  $boolUserinfo ? " (außer Alter und Verkehrsmittel) " : ""?>und im Rahmen der 
        <a href="datenschutz.php" target="_blank">Datenschutzerklärung</a> 
         verarbeitet werden dürfen.<br>
<?php if ($boolUpload): ?>  
Wenn Sie ein <strong>Bild</strong> hochladen, achten Sie bitte auf 
<strong>Urheber- und Persönlichkeitsrechte</strong>.
<?php endif;  ?>                              
</label>
<input type="submit" id="submit" class="btn btn-primary" tabindex="-1">
</form>

    </div> <!-- card-body -->
</div> <!-- card -->

