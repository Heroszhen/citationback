<?php

namespace App\Controller;


use App\Entity\Citation;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Validator\ContainsProductname;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\File as ConstraintFile;


class CitationImageController extends AbstractController
{
    /*
    private $validator;
    private $constraintFile;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        $this->constraintFile = new ConstraintFile(
            null, 
            '4M', 
            null, 
            ["image/jpeg", "image/png","image/jpg", "image/gif"],
            null,
            null,
            null,
            "Veuillez envoyer une image au format jpeg, png ou gif."
        );
    }

    public function __invoke(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $request->request;

        $user = $em->find(User::class, 1);
        $citation = new Citation();
        $citation
            ->setUser($user)
            ->setMessage($form->get("message"))
            ->setUpdatedAt(new \DateTimeImmutable());

        $image = $request->files->get("image");
        if($image != null){
            $citation->setImage($image);
            $errors = $this->validator->validate(
                $citation->getImage(),
                $this->constraintFile
            );
            if($errors->count() > 0){
                return $this->json(["errors" => $errors]);
            }

            $citation
                ->setImage(null)
                ->setFilename2(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME))
                ->setFiletype($image->guessExtension())
                ->setFilesize($image->getSize());
            $newname = uniqid().".".$citation->getFiletype();
            $citation->setFilename($newname);
            $image->move($this->getParameter('citation_images')."/", $newname);
        }
        return $citation;
    }*/

    public function __invoke(Request $request): Citation
    {
        $em = $this->getDoctrine()->getManager();

        $form = $request->request;
        $citation = new Citation();
        $user = $em->find(User::class, 1);
        $citation->setUser($user);
        if($form->get("message")) {
            $citation->setMessage($form->get("message"));
        }

        $image = $request->files->get("image");
        if($image != null) {
            $citation
                ->setImage($image)
                ->setUpdatedAt(new \DateTimeImmutable());
        }

        return $citation;
    }

}