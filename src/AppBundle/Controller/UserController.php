<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class UserController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function indexAction(Request $request)
    {
        
        $data['username'] = $request->get('username');
        $data['password'] = $request->get('password');
        //prx($data);
        
        $sql2 = 'SELECT * FROM user where email="'.$data['username'].'" and password="'.base64_encode($data['password']).'"';
        $em = $this->getDoctrine()->getManager();
       
        $dataSet = $em->getConnection()
                    ->fetchAll($sql2);
        if(count($dataSet) > 0){
            $session = $request->getSession();
            $session->remove('user');
            $session->set('user',$dataSet[0]);
            $return['success'] = '1';
            $return['redirect_url']     =   $this->generateUrl('doctors_listing');
        }else{
            $return['success'] = '0';
        }
        return new JsonResponse($return);
    }
    
    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(Request $request)
    {
        $session = $request->getSession();        
        $session->remove('user');
        return $this->redirect($this->generateUrl("homepage"));
    }
    
    /**
     * @Route("/sendmail", name="sendmail")
     */
    public function sendmailAction(Request $request)
    {
       
        $data['username'] = $request->get('username');
        
        $sql2 = 'SELECT * FROM user where email="'.$data['username'].'"';
        $em = $this->getDoctrine()->getManager();
        
        $dataSet = $em->getConnection()
                    ->fetchAll($sql2);
        //print_r($dataSet); exit;
        if(count($dataSet) > 0){
            $session = $request->getSession();
            $session->remove('user');
            $email = trim($data['username']);
            
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $password = substr( str_shuffle( $chars ), 0, 8);
            
            $update_qry = "UPDATE user set password = '".base64_encode($password)."' where email= '".$email."'";
            $em = $this->getDoctrine()->getManager();
            $conn = $em->getConnection();
            $conn->prepare($update_qry)->execute();
        
                $message = (new \Swift_Message('Password Reset - WB Analysis'))
                        ->setFrom('noreply@waybeyond.in')
                        ->setTo($email)
                        ->setBody(
                            "<p>Hi,</p>
                                <p>Your new password for <strong>".$email."</strong> has been reset to&nbsp; <strong>".$password."</strong>.</p>
                                <p>Thanks,</p>
                                <p>WB Analysis Portal</p>",
                            'text/html'
                        );
            $this->get('mailer')->send($message);
            $return['success'] = '1';
        }else{
            $return['error'] = 'User not found.';
            $return['success'] = '0';
        }
        //homepage
        $return['redirect_url'] = $this->generateUrl('homepage');
        
        return new JsonResponse($return);
    }
}
