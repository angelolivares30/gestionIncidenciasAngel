<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Form\ClienteType;
use App\Repository\ClienteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ClienteController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/insertarCliente', name: 'app_cliente')]
    public function index(Request $request): Response
{
    $cliente = new Cliente();
    $form = $this->createForm(ClienteType::class, $cliente);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        $this->em->persist($cliente);
        $this->em->flush();
        
        return $this->redirectToRoute('listaClientes'); // Redirigir a la lista de clientes después de enviar el formulario
    }
    
    return $this->render('cliente/index.html.twig', [
        'form' => $form->createView()
    ]);
}

    #[Route('/verClientes', name: 'listaClientes')]
    public function verClientes(Request $request, ClienteRepository $clienteRepository): Response
    {
        // Verificar si el usuario tiene acceso al rol ROLE_USER_AUTHENTICATED
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Acceso denegado');
        $cliente = $clienteRepository->findAll();
        return $this->render('cliente/verClientes.html.twig', ['clientes' => $cliente]);
        
    }

    #[Route('/cliente/delete/{id}', name:'deleteCliente')]
    public function delete(Cliente $cliente, EntityManagerInterface $entityManager):Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Acceso denegado');
        $entityManager->remove($cliente);
        $entityManager->flush();
        return $this->redirectToRoute('listaClientes');
    }

    #[Route('/cliente/incidencias/{id}', name: 'incidenciaPorCliente')]
    public function verIncidenciasPorCliente(Cliente $cliente, ClienteRepository $clienteRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Acceso denegado');
        $incidencias = $cliente->getIncidencias(); 
        return $this->render('cliente/incidenciasPorCliente.html.twig', [
            'cliente' => $cliente,
            'incidencias' => $incidencias,
        ]);
    }
}
