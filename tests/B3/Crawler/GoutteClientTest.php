<?php
namespace B3\Crawler;
use PHPUnit\Framework\TestCase;

use Symfony\Contracts\HttpClient\{
    HttpClientInterface,
    ResponseInterface
};
use B3\Decorator\DecoratorInterface;
/**
 * Scrapper test case.
 *
 */
class GoutteClientTest extends TestCase
{
    
    protected function mockHttpClient(string $method= 'GET', string $url, array $options = [] , int $responseStatus=200, array $responseHeaders = []){
        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $fnMockResponse = function($method) use($responseStatus, $responseHeaders){
            $folder = strtoupper($method);
            $content = file_get_contents(FIXTURES_DIR . "/http/responses/{$folder}/busca-empresa-listada.html");
            $result = $this->createMock(ResponseInterface::class);
            $result->expects($this->once())
                ->method('getContent')
                ->willReturn($content);
            $result->expects($this->once())
                ->method('getStatusCode')
                ->willReturn($responseStatus);    
            $result->expects($this->once())
                ->method('getHeaders')
                ->willReturn($responseHeaders);    
            return $result;
        };
        $createFnMockResponse = function($method) use($fnMockResponse)
        {
            return function() use($method, $fnMockResponse){
                return $fnMockResponse($method);
            };
        };

        $responseMap = [
            ['GET', $url, $createFnMockResponse('GET')],
            ['POST',$url, $createFnMockResponse('POST')],
        ];
        $mockHttpClient->method('request')
                        ->will($this->returnCallback(function() use($responseMap){
                            $args = func_get_args();
                            $foundMap = current(array_filter($responseMap , function($map) use($args){
                                return $args[0] == $map[0];
                            }));
                            
                            $fnMkResponse = array_pop($foundMap);                            
                            
                            $match = array_filter(array_keys($foundMap), function($key) use($foundMap, $args){
                                return $foundMap[$key] == $args[$key];
                            });                            
                            if($match){
                                return $fnMkResponse();
                            }                            
                            
                        }));
        
        return $mockHttpClient;
    }



     /**
     * 
     * @test
     *  
     * @return void
     */
    public function it_call_request_and_return_decorator():void
    {  
        $uri = 'http://b3.php.loc/scrapper/tests/fixtures/http/responses/GET/busca-empresa-listada.html';
        $method = "GET";
        $action = 'BuscaEmpresaListada.aspx?idioma=pt-br';
        $options = [];
        $parameters = [];
        $files = [];
        $responseHeaders = ['user-agent' => 'xpto'];        
        $mockHttpClient = $this->mockHttpClient($method, $uri,$options, 200);
        $goutteClient = di()->make(\Goutte\Client::class,[
            'client' => $mockHttpClient
        ]);
        $args = ['subject' => $goutteClient];
        
        $client = di()->make(GoutteClient::class, $args);
        
        $crawler = $client->request($method,$uri,$parameters);
        $link = $crawler->selectLink('Mercado internacional (BDRs)')->link();
        $this->assertInstanceof(DecoratorInterface::class, $link);// link decorator
        $this->assertInstanceOf(DecoratorInterface::class, $crawler);
        $button = $crawler->filter('[value="Todas"]');
        $this->assertInstanceOf(DecoratorInterface::class, $button);
        $buttonNode = $button->getNode(0);
        $this->assertEquals($buttonNode->getAttribute('value'),'Todas');
        $formDecorator = $button->form();
        $this->assertInstanceOf(DecoratorInterface::class, $formDecorator);

        // $textarea = $formDecorator->append('textarea', [
        //     'class' => 'xpto'
        // ]);
        // $buttonNode->SetAttribute('value','oi');

        
        $buttonId = $buttonNode->getAttribute('id');
        $listCrawler = $client->eventSubmit("#{$buttonId}");
        $this->assertInstanceOf(DecoratorInterface::class, $listCrawler);
        $firstRowText = $listCrawler->filter('.MasterTable_SiteBmfBovespa tbody tr:first-child td:first-child a')->text();                        
        $this->assertEquals('524 PARTICIPACOES S.A.', $firstRowText);

    }
}
