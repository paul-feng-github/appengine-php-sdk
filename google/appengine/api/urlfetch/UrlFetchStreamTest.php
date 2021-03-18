<?php
/**
 * Copyright 2021 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace google\appengine\api\urlfetch;

use google\appengine\testing\ApiProxyTestBase;
use google\appengine\URLFetchRequest;
use google\appengine\URLFetchRequest\RequestMethod;
use google\appengine\URLFetchResponse;

class UrlFetchStreamTest extends ApiProxyTestBase
{
    public function setUp()
    {
        parent::setUp();
        $this->_SERVER = $_SERVER;
    }

    public function tearDown()
    {
        $_SERVER = $this->_SERVER;
        parent::tearDown();
    }

    public function testStreamWithHeaderArray()
    {
        $urlfetch_stream = new UrlFetchStream();
        $url = "http://www.google.com";

        // Mock behavior of result.
        $req = new URLFetchRequest();
        $resp = new URLFetchResponse();
        $req->setUrl($url);
        $req->setMethod(RequestMethod::POST);
        $req->setFollowredirects(true);
        $req->setMustvalidateservercertificate(false);
        $header = new URLFetchRequest\Header();
        $header->setKey('Content-type');
        $header->setValue('application/x-www-form-urlencoded');
        $req->addHeader($header);
        $this->apiProxyMock->expectCall('urlfetch', 'Fetch', $req, $resp);
        // Result.
        $header_arr = ['Content-type' => 'application/x-www-form-urlencoded'];
        $opts = array('http' =>
        array(
            'method' => 'POST',
            'header'  => $header_arr,
        )
    );
        $opts = stream_context_create($opts);
        $urlfetch_stream->context = $opts;
        $result = $urlfetch_stream->stream_open($url, 'a+', $unused1, $unused2);
        $this->assertEquals(true, $result);
    }
  
    public function testStreamWithHeaderString()
    {
        $urlfetch_stream = new UrlFetchStream();
        $url = "http://www.google.com";

        // Mock behavior of result.
        $req = new URLFetchRequest();
        $resp = new URLFetchResponse();
        $req->setUrl($url);
        $req->setMethod(RequestMethod::POST);
        $req->setFollowredirects(true);
        $req->setMustvalidateservercertificate(false);
        $header = new URLFetchRequest\Header();
        $header->setKey('Content-type');
        $header->setValue('application/x-www-form-urlencoded');
        $req->addHeader($header);
        $this->apiProxyMock->expectCall('urlfetch', 'Fetch', $req, $resp);
        // Result.
        $header_str = 'Content-type: application/x-www-form-urlencoded';
        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header'  => $header_str,
            )
        );
        $opts = stream_context_create($opts);
        $urlfetch_stream->context = $opts;
        $result = $urlfetch_stream->stream_open($url, 'a+', $unused1, $unused2);
        $this->assertEquals(true, $result);
    }

    public function testStreamWithMultiHeaderString()
    {
        $urlfetch_stream = new UrlFetchStream();
        $url = "http://www.google.com";

        // Mock behavior of result.
        $req = new URLFetchRequest();
        $resp = new URLFetchResponse();
        $req->setUrl($url);
        $req->setMethod(RequestMethod::POST);
        $req->setFollowredirects(true);
        $req->setMustvalidateservercertificate(false);
        $header = new URLFetchRequest\Header();
        $header->setKey('Content-type');
        $header->setValue('application/octet-stream');
        $req->addHeader($header);
        $header = new URLFetchRequest\Header();
        $header->setKey('X-Google-RPC-Service-Deadline');
        $header->setValue('60');
        $req->addHeader($header);
        $header = new URLFetchRequest\Header();
        $header->setKey('X-Google-RPC-Service-Endpoint');
        $header->setValue('app-engine-apis');
        $req->addHeader($header);
        $header = new URLFetchRequest\Header();
        $header->setKey('X-Google-RPC-Service-Method');
        $header->setValue('/VMRemoteAPI.CallRemoteAPI');
        $header = new URLFetchRequest\Header();
        $header->setKey('User-Agent');
        $header->setValue('some_user_agent_string');
        $req->addHeader($header);

        $req->addHeader($header);
        $this->apiProxyMock->expectCall('urlfetch', 'Fetch', $req, $resp);
        // Result.
        $header_str = "Content-Type: application/octet-stream\r\n" .
            "X-Google-RPC-Service-Deadline: 60\n" . "X-Google-RPC-Service-Endpoint: app-engine-apis\r" .
            "X-Google-RPC-Service-Method: /VMRemoteAPI.CallRemoteAPI\n";

        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header'  => $header_str,
                'user_agent' => 'some_user_agent_string',
            )
        );
        $opts = stream_context_create($opts);
        $urlfetch_stream->context = $opts;
        $result = $urlfetch_stream->stream_open($url, 'a+', $unused1, $unused2);
        $this->assertEquals(true, $result);
    }

    public function testGetFetchWithPayload()
    {
        $urlfetch_stream = new UrlFetchStream();
        $url = "http://www.google.com";
    
        $payload = http_build_query(
            array(
                'var1' => 'some_content',
                'var2' => 'some_content2'
            )
        );
        $opts = array('http' =>
            array(
                'method' => 'POST',
                'content' => $payload
            )
        );
        // Mock behavior of result.
        $req = new URLFetchRequest();
        $resp = new URLFetchResponse();
        $req->setUrl($url);
        $req->setMethod(RequestMethod::POST);
        $req->setFollowredirects(true);
        $req->setMustvalidateservercertificate(false);
        $req->setPayload($payload);
        $this->apiProxyMock->expectCall('urlfetch', 'Fetch', $req, $resp);
        // Result.
        $opts = stream_context_create($opts);
        $urlfetch_stream->context = $opts;
        $result = $urlfetch_stream->stream_open($url, 'a+', $unused1, $unused2);
        $this->assertEquals(true, $result);
    }

    public function testGetFetchWithDeadline()
    {
        $urlfetch_stream = new UrlFetchStream();
        $url = "http://www.google.com";
        $deadline = 5.0;
        $opts = array('http' =>
            array(
                'method' => 'POST',
                'timeout' => $deadline
            )
        );
        // Mock behavior of result.
        $req = new URLFetchRequest();
        $resp = new URLFetchResponse();
        $req->setUrl($url);
        $req->setMethod(RequestMethod::POST);
        $req->setFollowredirects(true);
        $req->setMustvalidateservercertificate(false);
        $req->setDeadline($deadline);
        $this->apiProxyMock->expectCall('urlfetch', 'Fetch', $req, $resp);
        // Result.
        $opts = stream_context_create($opts);
        $urlfetch_stream->context = $opts;
        $result = $urlfetch_stream->stream_open($url, 'a+', $unused1, $unused2);
        $this->assertEquals(true, $result);
    }

    public function testGetFetchWithFileGetContents()
    {
        $url = "http://www.google.com";
        $deadline = 5.0;
        $opts = array('http' =>
            array(
                'method' => 'GET',
                'timeout' => $deadline
            )
        );

        // Mock behavior of result.
        $req = new URLFetchRequest();
        $resp = new URLFetchResponse();
        $req->setUrl($url);
        $req->setMethod(RequestMethod::GET);
        $req->setFollowredirects(true);
        $req->setMustvalidateservercertificate(false);
        $req->setDeadline($deadline);
        $this->apiProxyMock->expectCall('urlfetch', 'Fetch', $req, $resp);
        // Result.
        stream_wrapper_unregister("http");
        stream_wrapper_register("http", "google\appengine\api\urlfetch\UrlFetchStream")
        or die("Failed to register http protocol for UrlFetchStream");
        $opts = stream_context_create($opts);
        $result = file_get_contents($url, false, $opts);
    }
}
