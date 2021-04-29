<?php require_once 'head.view.php'; ?>

        <script type="text/javascript">console.log("tabla empresa, campo mercado e idindustria")</script>

        <div class="container">                       
            <div class="row principal">
                <div class="col-md-12">
                    <div class="titulo text-center">
                        <h2>Agrupación</h2>
                    </div>
                    <p class="sub_titulo">Seleccione la tabla para generar los superqueries</p>
                    <form method="post">
                        <div class="form-row">
                            <div class="form-group col-xs-12 col-md-5">
                                <label for="tabla">Tabla</label>                                
                                <select class="form-control" id="tabla" name="tabla">
                                    <option value="-- Seleccione una tabla --">--Seleccione una tabla--</option>
                                    <?php                                     
                                        foreach($Tablas as $opcion)
                                            echo '<option value='.$opcion.'>'.$opcion.'</option>';                                        
                                    ?>                                    
                                </select><br/><br/>

                                <div id="divcampos"></div>                                
                            </div>

                            <div class="form-group col-xs-12 col-md-6 offset-md-1">
                                <label for="query1">Super query 1</label>
                                <textarea class="form-control" id="query1" rows="4" style="resize: none;"><?php echo $query1; ?></textarea><br/>
                                
                                <label for="query2">Super query 2</label>
                                <textarea class="form-control" id="query2" rows="4" style="resize: none;"><?php echo $query2; ?></textarea>
                                <br/><br/>    
                                <input type="submit" class="boton" name="Generar" value="Generar"/>
                            </div>
                            
                        </div>
                        
                    </form>
                </div>
            </div>

            <div class="row principal">
                <div class="col-md-5">
                    <?php if(!empty($SuperQuery1)): ?> 
                        <table class="table table-hover table-striped table-light table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col"><?php echo $campo1; ?></th>
                                    <th scope="col">Probabilidad</th>
                                </tr>
                            </thead>
                            <tbody><?php echo $SuperQuery1; ?></tbody>
                        </table>            
                    <?php endif; ?>
                </div>

                <div class="col-md-6 offset-md-1">
                    <?php if(!empty($SuperQuery2)): ?> 
                        <table class="table table-hover table-striped table-light table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col"><?php echo $campo1; ?></th>
                                    <th scope="col"><?php echo $campo2; ?></th>
                                    <th scope="col">Probabilidad</th>
                                </tr>
                            </thead>
                            <tbody><?php echo $SuperQuery2; ?></tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

             <div class="row">
                <div class="col-md-5">
                    <? if(!empty($jsonQuery1)): ?> 
                        <div>
                            <h6><?php echo $jsonQuery1; ?></h6>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6 offset-md-1">
                    <? if(!empty($jsonQuery2)): ?> 
                        <div>
                            <h6><?php echo $jsonQuery2; ?></h6>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <!--<div class="row principal">
                <div class="col-md-12">
                    <div id="myDiagramDiv" style="background-color: white; border: solid 1px black; width: 100%; height: 700px"></div>				
                </div>
            </div>-->
        </div>
        
        
        <br/><br/>        
<?php require_once 'footer.view.php'; ?>
<script type="text/javascript" >
    $(document).ready(function(){
        $('#tabla').val('-- Seleccione una tabla --');
        recargarLista();        
        $('#tabla').change(function(){
            recargarLista();            
        });
    })    

    function recargarLista(){
        $.ajax({
            type:"POST",
            url:"droplist.php",
            data:"tabla=" + $('#tabla').val(),
            success:function(r){
                $('#divcampos').html(r);
            }
        });
    }
    
</script>
