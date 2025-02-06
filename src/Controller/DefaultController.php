<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Functions;
use App\Entity\Record;

class DefaultController extends ApiController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route(path: '/', name: 'homepage')]
    public function index(AuthenticationUtils $authenticationUtils, Functions $func): Response
    {
        $user = $this->getUser();

        if ($user) {
            $month = date('m');
            $year = date('Y');
            $day = date('d');
            $entity = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $monthName = $func->getMonthName($month);
            //var_dump($number);

            // пользователь авторизован
            return $this->render('default/index.html.twig', [
                'day' => $day,
                'month' => $month,
                'monthName' => $monthName,
                'year' => $year,
                'entity' => $entity,
            ]);
        } else {
            // пользователь не авторизован
            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();

            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();

            return $this->render('security/login.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error,
            ]);
        }
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        
    }

    #[Route(path: '/api/shedule/', name: 'app_shedule', methods: 'GET')]
    public function getShedule(Functions $func)
    {
        $month = date('m');
        $year = date('Y');
        $day = date('d');
        $dayInt = date('j');
        $entity = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $monthName = $func->getMonthName($month);

        $dates = [];
        for($i = $dayInt; $i <= $entity; $i++){
            $d = $i < 10 ? '0' . $i : $i;
            $dates[] = $year . '.' . $month . '.' . $d;
        }

        $user = $this->getUser();

        $arRecords = $this->entityManager->getRepository(Record::class)
                        ->getRecords($user->getId());

        $records = [];

        foreach($arRecords as $record){
            if($record->getDate()){
                $records[] = [
                    'date' => $record->getDate()->format('Y.m.d'),
                    'time' => $record->getTime(),
                    'item' => $record->getItem()

                ];
            }
        }

        $arrSchedule = [
            'day' => $day,
            'month' => $month,
            'monthName' => $monthName,
            'year' => $year,
            'entity' => $entity,
            'records' => $records,
            'dates' => $dates
        ];

        return $this->respond($arrSchedule);
    }

    #[Route(path: '/api/add_item/', name: 'add_item', methods: 'POST')]
    public function addItem(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $time = $data['time'];
        $item = $data['item'];
        $date = $data['date'];

        $entity = new Record();
        $user = $this->getUser();
        $em = $this->entityManager;

        $entity->setTime($time);
        $entity->setItem($item);
        $entity->setUser($user);
        $entity->setDate(new \DateTime($date));

        $em->persist($entity);
        $em->flush();

        return $this->respond($data);
    }
}
