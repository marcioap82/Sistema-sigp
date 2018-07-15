<?php
class AudienciaService extends AdiantiRecordService
{
	const DATABASE = "sigp";
	const ACTIVE_RECORD = "Audiencia";


    public function getPessoa($param)
    {
      $id = $param['id'];
       TTransaction::open('sigp'); // open transaction
          
      $conn = TTransaction::get();
       $result = $conn->query("SELECT DISTINCT c.data_da_audiencia, c.local_da_audiencia, c.hora_da_audiencia, a.nome FROM Pessoa a , pessoa_audiencia b, audiencia c WHERE a.id = b.id_pessoa and c.id = b.id_audiencia and b.id_pessoa=$id GROUP BY c.data_da_audiencia, c.local_da_audiencia, c.hora_da_audiencia, a.nome");
          
    
     if($result)
     {
     	$resultado = [];
     	$i=0;
     	foreach ($result as $row =>$valor) {
     		$resultado[$row]=$valor;
      		} 
     		
     		    
     }
  return $resultado;
     TTransaction::close();
       
    }

	
}