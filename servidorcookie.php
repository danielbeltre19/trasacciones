<?php

class servidorcookie implements Iserviciobase{
 
    private $utilities;
    private $cookienombre;
    
    public function __construct(){
        
        $this->servicio = new Servicio();
        $this->cookienombre = "estudiante";
    }
public function Getlista(){

$listaestudiante = array();

if(isset($_COOKIE[$this->cookienombre])){

$listaestudiantedecode= json_decode ($_COOKIE[$this->cookienombre],false);

   
foreach ($listaestudiantedecode as $elementoD) {
    $element=new estudiante();
$element->set($elementoD);
    
        array_push($listaestudiante,$element);
    }


}else{
    setcookie($this->cookienombre,json_encode($listaestudiante),$this->servicio->cookietime(),"/");
}
return $listaestudiante;

}

public function GetByid($id){
    
    $listaestudiante = $this->Getlista();
    $estudiante = $this->servicio->buscar($listaestudiante,'id',$id)[0];
    return $estudiante;
}

public function añadir($entidad)
{
    $listaestudiante=$this->Getlista();
    $estudianteid=1;
    
    if(!empty($listaestudiante)){
        $lastestudiante=$this->servicio->getLastElement($listaestudiante);
        $estudianteid=$lastestudiante->id+1;
    }
$entidad->id=$estudianteid;
$entidad->profilePhoto = "";

if(isset($_FILES['profilePhoto'])){
    
    $photofile=$_FILES['profilePhoto'];

    if($photofile['error']==4){
    
    $entidad->profilePhoto = "";
    
   }else{

$typeReplace = str_replace("image/", "", $_FILES['profilePhoto']['type']);
 $type= $photofile['type'];
 $size= $photofile['size'];
 $name= $estudianteid . '.' . $typeReplace;
 $tmpname= $photofile['tmp_name'];
 
 $success=$this->servicio->uploadImage('imagenes/estudiante/',$name,$tmpname,$type,$size);
 
 if($success){
     $entidad->profilePhoto= $name;
 }
}
}

array_push($listaestudiante,$entidad);

 setcookie($this->cookienombre,json_encode($listaestudiante),$this->servicio->cookietime(),"/");
    
}

public function editar($id,$entidad){
    
$elemento=$this->GetByid($id);
    $listaestudiante = $this->Getlista();

    $elementoindex=$this->servicio->getelemento($listaestudiante,'id',$id);

if(isset($_FILES['profilePhoto'])){
    
    $photofile=$_FILES['profilePhoto'];
    
if($photofile['error']==4){
    
    $entidad->profilePhoto = $elemento->profilePhoto;
    
}else{
    
$typeReplace = str_replace("image/", "", $_FILES['profilePhoto']['type']);
 $type= $photofile['type'];
 $size=$photofile['size'];
 $name=$id . '.' . $typeReplace;
 $tmpname=$photofile['tmp_name'];
 
 $success=$this->servicio->uploadImage('imagenes/estudiante/',$name,$tmpname,$type,$size);
 
 if($success){
     $entidad->profilePhoto= $name;
 }
    
}
}
 
$listaestudiante[$elementoindex]=$entidad;
setcookie($this->cookienombre,json_encode($listaestudiante),$this->servicio->cookietime(),"/");
    
}


    public function eliminar($id){

        
        $listaestudiante=$this->Getlista();
        $elementoindex=$this->servicio->getelemento($listaestudiante,'id',$id);

unset($listaestudiante[$elementoindex]);
$listaestudiante=array_values($listaestudiante);
setcookie($this->cookienombre,json_encode($listaestudiante),$this->servicio->cookietime(),"/");



}

}




?>