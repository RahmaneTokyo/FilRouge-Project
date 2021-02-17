<?php


namespace App\Service;

use App\Entity\Admin;
use App\Entity\Apprenant;
use App\Entity\Cm;
use App\Entity\Formateur;
use App\Repository\ProfilRepository;
use App\Repository\PromoRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{

    /**
     * @var DenormalizerInterface
     */
    private $serializer;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    private $repo;
    /**
     * @var ProfilRepository
     */
    private $profilRepository;
    /**
     * @var PromoRepository
     */
    private $promoRepository;

    public function __construct(UserPasswordEncoderInterface $encoder, DenormalizerInterface $serializer, ProfilRepository $profilRepository, PromoRepository $promoRepository)
    {
        $this->encoder = $encoder;
        $this->serializer = $serializer;
        $this->profilRepository = $profilRepository;
        $this->promoRepository = $promoRepository;
    }

    public function addApprenant($profil, Request $request, ValidatorInterface $validator,ProfilRepository $repo)
    {
        $userTab = $request->request->all();
        $uploadedfile = $request->files->get('avatar');
        if ($uploadedfile)
        {
            $file = $uploadedfile->getRealPath();
            $avatar = fopen($file, 'r+');
            $userTab['avatar'] = $avatar;
        }
        if($profil == "APPRENANT"){
            $userType = Apprenant::class;
        }
        $user = $this->serializer->denormalize($userTab, $userType);
        $user->setProfil($repo->findOneBy(['libelle'=>$profil]));
        $error = $validator->validate($user);
        if(count($error)>0){
            throw new BadRequestException($error);
        }else{
            $user->setPassword($this->encoder->encodePassword($user, $userTab['password']));
            return $user;
        }
    }


    public function addUser($profil, Request $request, ValidatorInterface $validator)
    {
        $userTab = $request->request->all();
        $uploadedfile = $request->files->get('avatar');
        if ($uploadedfile)
        {
            $file = $uploadedfile->getRealPath();
            $avatar = fopen($file, 'r+');
            $userTab['avatar'] = $avatar;
        }
        if($userTab['profil'] == "ADMIN"){
            $userType = Admin::class;
        }elseif($userTab['profil'] == "FORMATEUR"){
            $userType = Formateur::class;
        }elseif($userTab['profil'] == "APPRENANT"){
            $userType = Apprenant::class;
        }elseif($userTab['profil'] == "CM"){
            $userType = Cm::class;
        }
        //dd($user);
        $idProfil = $this->profilRepository->findOneBy(['libelle' =>$profil])->getId();
        $userTab['profil'] = "api/admin/profils/".$idProfil;
        $user = $this->serializer->denormalize($userTab, $userType);
        $error = $validator->validate($user);
        if(count($error)>0){
            throw new BadRequestException($error);
        }else{
            $user->setPassword($this->encoder->encodePassword($user, $userTab['password']));
            $user->setProfil($this->profilRepository->findOneBy(['libelle' =>$profil]));
            return $user;
        }
    }


    public function UpdateUser(Request $request, string $filename = null)
    {
        $row = $request->getContent();
        //dd($row);
        $delimitor = "multipart/form-data; boundary=";
        $boundary = "--".explode($delimitor, $request->headers->get("content-type"))[1];
        //dd($boundary);
        $elements = str_replace([$boundary,'Content-Disposition: form-data;',"name="],"",$row);
        //dd($elements);
        $tabElements = explode("\r\n\r\n", $elements);
        //dd($tabElements);
        $data = [];

        for ($i = 0; isset($tabElements[$i+1]); $i++)
        {
            $key = str_replace(["\r\n",'"','"'],'',$tabElements[$i]);
            //dd($key);
            $key = trim($key);
            //dd($key);
            if (strchr($key, $filename))
            {
                $file = fopen('php://memory', 'r+');
                fwrite($file, $tabElements[$i+1]);
                rewind($file);
                $data[$filename] = $file;
                //dd($data);
            }else {
                $val = str_replace(["\r\n",'--'], '', $tabElements[$i+1]);
                $data[$key] = $val;
            }
        }
        //dd($data);
        //dd($data['profil']);
        $profil = $this->profilRepository->findOneBy(['libelle' =>$data['profil']]);
        //dd($profil);
        $data['profil'] = $profil;
        //dd($data);

        return $data;
    }

    public function getApprenantAttente()
    {
        return $this->promoRepository->findApprenantAttente();
    }
}