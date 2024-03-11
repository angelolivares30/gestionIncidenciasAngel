<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[Route('/registro', name: 'userRegistro')]
    public function userRegistro(Request $request, UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger): Response
    {
        $user = new User();
        $registration_form = $this->createForm(UserType::class, $user);
        $registration_form->handleRequest($request);
        if ($registration_form->isSubmitted() && $registration_form->isValid()) {
            $file = $registration_form->get('foto')->getData();
            $plaintexPassword = $registration_form->get('password')->getData();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintexPassword
            );
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('error, has tenido errores con tu imagen');
                }
                $user->setFoto($newFilename);
            }
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_USER']);
            $this->em->persist($user);
            $this->em->flush();
            return $this->redirectToRoute('login');
        }
        return $this->render('user/index.html.twig', [
            'registration_form' => $registration_form->createView(),
        ]);
    }
}
