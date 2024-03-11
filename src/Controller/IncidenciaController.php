<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\Incidencia;
use App\Form\IncidenciaType;
use App\Repository\IncidenciaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IncidenciaController extends AbstractController
{

    #[Route('/verIncidencias', name: 'ver_incidencias')]
public function verIncidencias(IncidenciaRepository $incidenciaRepository): Response
{
    $this->denyAccessUnlessGranted('ROLE_USER', null, 'Acceso denegado');
    // Obtener todas las incidencias del repositorio
    $incidencias = $incidenciaRepository->findAll();
    return $this->render('incidencia/verIncidencias.html.twig', [
        'incidencias' => $incidencias,
    ]);
}
    #[Route('/incidencia/add/{id}', name: 'insertarIncidencia')]
    public function addIncidencia(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Acceso denegado');
         $incidencia = new Incidencia();
         $formularioIncidencia = $this->createForm(IncidenciaType::class, $incidencia);
         $formularioIncidencia->handleRequest($request);
         if($formularioIncidencia->isSubmitted() && $formularioIncidencia->isValid()){
            
            $incidencia = $formularioIncidencia->getData();
            $entityManager->persist( $incidencia );
            $entityManager->flush();
            return $this->redirectToRoute('incidenciaPorCliente',['id'=>$id]);
         }

        return $this->render('incidencia/addIncidencia.html.twig', [
            'formularioIncidencia' => $formularioIncidencia
        ]);
    }

    #[Route('/incidencia/delete/{id}', name:'deleteIncidencia')]
    public function delete(Incidencia $incidencia , EntityManagerInterface $entityManager):Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Acceso denegado');
        $entityManager->remove($incidencia);
        $entityManager->flush();
        $clienteId = $incidencia->getCliente()->getId();
        return $this->redirectToRoute('incidenciaPorCliente',['id' => $clienteId]);
    }

    #[Route('/incidencia/actualizar/{id}', name: 'actualizarIncidencia')]
public function actualizarIncidencia(Incidencia $incidencia, Request $request, EntityManagerInterface $entityManager): Response
{
    $this->denyAccessUnlessGranted('ROLE_USER', null, 'Acceso denegado');
    $formularioIncidencia = $this->createForm(IncidenciaType::class, $incidencia);
    $formularioIncidencia->handleRequest($request);

    if ($formularioIncidencia->isSubmitted() && $formularioIncidencia->isValid()) {
        $entityManager->flush();

        $this->addFlash('success', 'La incidencia se ha actualizado correctamente.');

        $clienteId = $incidencia->getCliente()->getId();
        return $this->redirectToRoute('incidenciaPorCliente', ['id' => $clienteId]);
    }

    return $this->render('incidencia/updateIncidencia.html.twig', [
        'formularioIncidencia' => $formularioIncidencia->createView()
    ]);
}

    

    

}
