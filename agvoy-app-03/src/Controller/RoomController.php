<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

/**
 * @Route("/room")
 */
class RoomController extends AbstractController
{
    /**
     * @Route("/", name="room_index", methods={"GET"})
     */
    public function index(RoomRepository $roomRepository): Response
    {   
        $rooms=$roomRepository->findAll();
        foreach ($rooms as $room) {
            $message = '';
            $likes = $this->get('session')->get('likes');
            if (($likes) and (in_array($room->getId(), $likes))) {
                $message = 'remove';
            } else {
                $message = 'add';
            }
        }
        return $this->render('room/index.html.twig', [
            'rooms' => $roomRepository->findAll(),'message'=>$message
        ]);
    }

    /**
     * @Route("/new", name="room_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_OWNER', null, 'Access denied!');
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($room);
            $entityManager->flush();

            return $this->redirectToRoute('room_index');
        }

        return $this->render('room/new.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="room_show", methods={"GET"})
     */
    public function show(Room $room): Response
    {
        return $this->render('room/show.html.twig', [
            'room' => $room,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="room_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Room $room, User $user): Response
    {
        if ((!$user->getOwner()) or ($user->getOwner()->getId() != $room->getOwner()->getId())){
               $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Access denied!') ;
        }else{
               $this->denyAccessUnlessGranted('ROLE_OWNER', null, 'Access denied!') ;
        }
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('room_index');
        }

        return $this->render('room/edit.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="room_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Room $room): Response
    {
        $this->denyAccessUnlessGranted('ROLE_OWNER', null, 'Access denied!') ;
        #if (!$user->getOwner() && ($user->getOwner()->getId() != $room->getOwner()->getId())){
        #    console.log('hi');
        #    $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Access denied!') ;
        #}else{
        #    $this->denyAccessUnlessGranted('ROLE_OWNER', null, 'Access denied!') ;
        #}
        if ($this->isCsrfTokenValid('delete'.$room->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($room);
            $entityManager->flush();
        }

        return $this->redirectToRoute('room_index');
    }
    /**
     * @Route("/likes/{id}", name="room_like",requirements={ "id": "\d+"},methods="GET")
     */
    
    public function likes($id)
    {
        $likes = $this->get('session')->get('likes');
        if ($likes) {
            if (in_array($id, $likes)){
                unset($likes[array_search($id, $likes)]);
            } else {
                array_push($likes, $id);
            }
        }else{
                $likes=array();
                array_push($likes, $id);
        }
        $this->get('session')->set('likes', $likes);
        return $this->redirectToRoute("room_index");
    }
}
