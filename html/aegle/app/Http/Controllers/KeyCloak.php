<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Lcobucci\JWT\Parser;
use Mockery as m;
use Stevenmaguire;
use Session;

class KeyCloak extends Controller
{    
    protected $provider;
    
    public function show(){

        $provider = new Stevenmaguire\OAuth2\Client\Provider\Keycloak([
            'authServerUrl'     => 'http://snf-699683.vm.okeanos.grnet.gr/auth',
            'realm'             => 'AEGLE',
            'clientId'          => 'aegle_frontend',
            'clientSecret'      => '2eb4046b-bc73-4d7e-b93b-5f64c73dde52',
            'redirectUri'       => 'http://83.212.97.243/aegle/public/index.php/keycloak',
        ]);



        if (!isset($_GET['code'])) {

            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl();
            $_SESSION['oauth2state'] = $provider->getState();
            header('Location: '.$authUrl);
            exit;

        } else {

            // Try to get an access token (using the authorization coe grant)
            try {
                $token = $provider->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);
            } catch (Exception $e) {
                exit('Failed to get access token: '.$e->getMessage());
            }

            // Optional: Now you have a token you can look up a users profile data
            try {

                // We got an access token, let's now get the user's details
                $user = $provider->getResourceOwner($token);
                $token = $provider->getAccessToken('refresh_token', ['refresh_token' => $token->getRefreshToken()]);

                // Use these details to create a new profile
                //printf('Hello %s!', $user->getName());
                //printf('$token %s!', $token);

                //echo json_encode(ebase64_decode($token));

                $token = (new Parser())->parse((string) $token); // Parses from a string
                $token->getHeaders(); // Retrieves the token header
                $token->getClaims(); // Retrieves the token claims                

                $user_role = $token->getClaim('realm_access'); // will print "1"               
                
                session_start();
                $_SESSION["user_role"]=$user_role->roles[0];

               return View('case.index', ['user_role' => $user_role->roles[0]]);

            } catch (Exception $e) {
                exit('Failed to get resource owner: '.$e->getMessage());
            }

                   // $token = $provider->getAccessToken('refresh_token', ['refresh_token' => $token->getRefreshToken()]);

                     //   dd($token);
                        
                       // if(!$token){
                         //   echo 'no token available';
                        //}else{ echo 'available';}

            // Use this to interact with an API on the users behalf
            //echo $token->getToken();
        }
    }
    

    protected function setUp()
    {
    	 $this->$provider = new Stevenmaguire\OAuth2\Client\Provider\Keycloak([
            'authServerUrl'     => 'http://snf-699683.vm.okeanos.grnet.gr/auth',
            'realm'             => 'AEGLE',
            'clientId'          => 'aegle_frontend',
            'clientSecret'      => '2eb4046b-bc73-4d7e-b93b-5f64c73dde52',
            'redirectUri'       => 'http://localhost:88/aegle/public/keycloak',
        ]);

        /*
        $this->provider = new \Stevenmaguire\OAuth2\Client\Provider\Keycloak([
            'authServerUrl' => 'http://mock.url/auth',
            'realm' => 'mock_realm',
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri' => 'none',
        ]);
        */

        $this->testAuthorizationUrl();
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }

    public function testAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        parse_str($uri['query'], $query);

        $this->assertArrayHasKey('client_id', $query);
        $this->assertArrayHasKey('redirect_uri', $query);
        $this->assertArrayHasKey('state', $query);
        $this->assertArrayHasKey('scope', $query);
        $this->assertArrayHasKey('response_type', $query);
        $this->assertArrayHasKey('approval_prompt', $query);
        $this->assertNotNull($this->provider->getState());
    }


    public function testScopes()
    {
        $options = ['scope' => [uniqid(),uniqid()]];
        $url = $this->provider->getAuthorizationUrl($options);
        $this->assertContains(urlencode(implode(',', $options['scope'])), $url);
    }

    public function testGetAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);

        $this->assertEquals('/auth/realms/mock_realm/protocol/openid-connect/auth', $uri['path']);
    }

    public function testGetBaseAccessTokenUrl()
    {
        $params = [];

        $url = $this->provider->getBaseAccessTokenUrl($params);
        $uri = parse_url($url);

        $this->assertEquals('/auth/realms/mock_realm/protocol/openid-connect/token', $uri['path']);
    }

    public function testGetAccessToken()
    {
        $response = m::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn('{"access_token":"mock_access_token", "scope":"email", "token_type":"bearer"}');
        $response->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);

        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')->times(1)->andReturn($response);
        $this->provider->setHttpClient($client);

        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);

        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertNull($token->getExpires());
        $this->assertNull($token->getRefreshToken());
        $this->assertNull($token->getResourceOwnerId());
    }

    public function testUserData()
    {
        $userId = rand(1000,9999);
        $name = uniqid();
        $nickname = uniqid();
        $email = uniqid();

        $postResponse = m::mock('Psr\Http\Message\ResponseInterface');
        $postResponse->shouldReceive('getBody')->andReturn('access_token=mock_access_token&expires=3600&refresh_token=mock_refresh_token&otherKey={1234}');
        $postResponse->shouldReceive('getHeader')->andReturn(['content-type' => 'application/x-www-form-urlencoded']);

        $userResponse = m::mock('Psr\Http\Message\ResponseInterface');
        $userResponse->shouldReceive('getBody')->andReturn('{"sub": '.$userId.', "name": "'.$name.'", "email": "'.$email.'"}');
        $userResponse->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);

        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')
            ->times(2)
            ->andReturn($postResponse, $userResponse);
        $this->provider->setHttpClient($client);

        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
        $user = $this->provider->getResourceOwner($token);

        $this->assertEquals($userId, $user->getId());
        $this->assertEquals($userId, $user->toArray()['sub']);
        $this->assertEquals($name, $user->getName());
        $this->assertEquals($name, $user->toArray()['name']);
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($email, $user->toArray()['email']);
    }

    /**
     * @expectedException League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function testErrorResponse()
    {
        $response = m::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn('{"error": "invalid_grant", "error_description": "Code not found"}');
        $response->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);

        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')->times(1)->andReturn($response);
        $this->provider->setHttpClient($client);

        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
    }

}
