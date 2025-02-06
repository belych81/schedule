<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\Functions;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;
    private $passwordHasher;
    private $functions;
    private EntityManagerInterface $entityManager;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher, 
        Functions $functions, 
        EntityManagerInterface $entityManager
        )
    {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
        $this->functions = $functions;
    }

    #[Route(path: '/register', name: 'app_register')]
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            );
            $user->setPassword($hashedPassword);

            /*$user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );*/
            $entityManager = $this->entityManager;

            try {
                $entityManager->persist($user);
                $entityManager->flush();
            }
            catch(\Exception $e){
                return $this->render('registration/register.html.twig', [
                    'confirmMessage' => 'Произошла ошибка. Попробуйте позже'
                ]);
            }

            return $this->render('registration/register.html.twig', [
                'confirmMessage' => 'Вы успешно зарегистрированы'
            ]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
