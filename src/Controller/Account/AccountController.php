<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Model\User\UseCase\View;
use App\Model\User\Service\S3Uploader;
use App\Model\User\UseCase\Account;
use App\Model\User\UseCase\News;
use App\Model\User\UseCase\Subscription;
use App\Model\User\UseCase\Likes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'account_home', methods: ['GET'])]
    public function home(Request $request, View\AccountHome\Handler $handler): Response
    {
        $user = $this->getUser();

        if (is_null($account = $user->getAccount())) {
            return $this->redirectToRoute('account');
        } else {
            return $this->render('account/home.html.twig', ['dto' => $handler->handle($account)]);
        }
    }

    #[Route('/account/create', name: 'account', methods: ['GET', 'POST'])]
    public function create(Request $request, Account\Create\Handler $handler, S3Uploader $uploader): Response
    {
        $command = new Account\Create\Command();

        $form = $this->createForm(Account\Create\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $command->user = $this->getUser();
            $command->avatarKey = $key = uniqid();
            try {
                $handler->handle($command, $uploader->putObject($key, $command->avatar));
                return $this->redirectToRoute('account_home');
            } catch (\DomainException $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
        }

        return $this->render('account/account_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/account/list', name: 'list_users', methods: ['GET'])]
    public function list(Request $request, View\ListOfUser\Handler $handler): Response
    {
        $user = $this->getUser();
        $accounts = $handler->handle($user->getAccount());

        if (count($accounts) < 1) {
            $this->addFlash('error', 'No users');
            return $this->redirectToRoute('account_home');
        } else {
            return $this->render('account/show_all.html.twig', [
                'result' => $accounts
            ]);
        }
    }

    #[Route('/account/user/{id}', name: 'show_user', methods: ['GET'])]
    public function user(int $id, Request $request, View\ShowUser\Handler $handler): Response
    {
        return $this->render('account/user.html.twig', ['showUser' => $handler->handle($id, $this->getUser()->getAccount())]);
    }

    #[Route('/account/user/{id}/like', name: 'like_image', methods: ['GET'])]
    public function like(int $id, Request $request, Likes\CreateLike\Handler $handler): Response
    {
        $handler->handle($id, $this->getUser()->getAccount());

        return $this->redirectToRoute('show_user', [
            'id' => $id
        ]);
    }

    #[Route('/account/user/{id}/dislike', name: 'dislike_image', methods: ['GET'])]
    public function dislike(int $id, Request $request, Likes\DeleteLike\Handler $handler): Response
    {
        $handler->handle($id, $this->getUser()->getAccount());

        return $this->redirectToRoute('show_user', [
            'id' => $id
        ]);
    }

    #[Route('/account/user/{id}/subscribe', name: 'new_subscription', methods: ['GET'])]
    public function subscribe(int $id, Request $request, Subscription\Subscribe\Handler $handler): Response
    {
        $handler->handle($id, $this->getUser()->getAccount());

        return $this->redirectToRoute('show_user', [
            'id' => $id
        ]);
    }

    #[Route('/account/user/{id}/unsubscribe', name: 'delete_subscription', methods: ['GET'])]
    public function unsubscribe(int $id, Request $request, Subscription\DeleteSubscription\Handler $handler): Response
    {
        $handler->handle($id, $this->getUser()->getAccount());

        return $this->redirectToRoute('show_user', [
            'id' => $id
        ]);
    }

    #[Route('/account/subs', name: 'my_subscriptions', methods: ['GET'])]
    public function subs(Request $request, View\Subscription\Handler $handler): Response
    {
        $result = $handler->handle($this->getUser()->getAccount(), 'subs');

        if (count($result) < 1) {
            $this->addFlash('error', "You don't have any subscriptions");
            return $this->redirectToRoute('account_home');
        }

        return $this->render('account/followers.html.twig', [
            'result' => $result,
            'header' => 'My subscriptions'
        ]);
    }

    #[Route('/account/followers', name: 'my_followers', methods: ['GET'])]
    public function followers(Request $request, View\Subscription\Handler $handler): Response
    {
        $result= $handler->handle($this->getUser()->getAccount());

        if (count($result) < 1) {
            $this->addFlash('error', "You don't have any followers");
            return $this->redirectToRoute('account_home');
        }

        return $this->render('account/followers.html.twig', [
            'result' => $result,
            'header' => 'My followers'
        ]);
    }

    #[Route('/account/edit/info', name: 'account_edit_info', methods: ['GET', 'POST'])]
    public function editInfo(Request $request, Account\EditInfo\Handler $handler): Response
    {
        $command = new Account\EditInfo\Command();

        $form = $this->createForm(Account\EditInfo\Form::class, $command);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $command->user = $user;
            try {
                $handler->handle($command);
                return $this->redirectToRoute('account_home');
            } catch (\DomainException $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
        }

        return $this->render('account/edit_info_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/account/edit/avatar', name: 'account_edit_avatar', methods: ['GET', 'POST'])]
    public function editAvatar(Request $request, Account\EditAvatar\Handler $handler, S3Uploader $uploader): Response
    {
        $command = new Account\EditAvatar\Command();

        $form = $this->createForm(Account\EditAvatar\Form::class, $command);
        $form->handleRequest($request);
        $user = $this->getUser();
        $account = $user->getAccount();

        if ($form->isSubmitted() && $form->isValid()) {
            $command->user = $user;
            $command->avatarKey = uniqid();
            $avatar = $uploader->changeObject($account->getAvatarKey(), $command->avatarKey, $command->avatar);
            try {
                $handler->handle($command, $avatar);
                return $this->redirectToRoute('account_home');
            } catch (\DomainException $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
        }

        return $this->render('account/edit_avatar_form.html.twig', [
            'avatar' => $account->getAvatar(),
            'form' => $form->createView()
        ]);
    }

    #[Route('/account/appraised', name: 'appraised', methods: ['GET'])]
    public function appraised(Request $request, View\Appraised\Handler $handler): Response
    {
        $result = $handler->handle($this->getUser()->getAccount());

        if (count($result) < 1) {
            $this->addFlash('error', "You don't have any likes");
            return $this->redirectToRoute('account_home');
        }

        return $this->render('account/appraised.html.twig', [
            'result' => $result
        ]);
    }

    #[Route('/account/news/create', name: 'create_news', methods: ['GET', 'POST'])]
    public function createNews(Request $request, News\AddNews\Handler $handler, S3Uploader $uploader): Response
    {
        $command = new News\AddNews\Command();

        $form = $this->createForm(News\AddNews\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $command->account = $this->getUser()->getAccount();
            $command->imageKey = $key = uniqid();
            try {
                $handler->handle($command, $uploader->putObject($key, $command->image));
                return $this->redirectToRoute('account_home');
            } catch (\DomainException $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
        }

        return $this->render('news/news_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/account/news/list', name: 'list_news', methods: ['GET'])]
    public function showNews(Request $request, View\ListOfNews\Handler $handler): Response
    {
        $result = $handler->handle($this->getUser()->getAccount());

        if (count($result) < 1) {
            $this->addFlash('error', 'No news');
            return $this->redirectToRoute('account_home');
        } else {
            return $this->render('news/show_news.html.twig', [
                'result' =>$result
            ]);
        }
    }
}
