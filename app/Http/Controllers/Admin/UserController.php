<?php
namespace App\Http\Controllers\Admin;

use App\Repository\Eloquent\UserRepository;
use App\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Hash;
use App\Helpers\PushNotification;
use App\Helpers\TransFormatApi;
use Illuminate\Support\Facades\DB;
use Validator;

/**
 * @property UserRepository userRepository
 */
class UserController extends AdminBaseController
{
    protected $userRepository;


    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository
    )
    {
        parent::__construct();

        $this->userRepository = $userRepository;
    }
}