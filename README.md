# support
common components

## ResponseTriat Usage
用于规范统一响应消息体结构

```PHP
use zlink\Supports\Triats\ResponseTriat;

class BaseController extends Controller
{
    use ResponseTriat;

    public function index()
    {
        $headers = [
            'Cache-Control': 'no-cache',
            'Content-Type': 'text/html; charset=utf-8',
        ];
        
        $message = 'request success';
        
        $data = [];
        
        // 直接返回
        return $this->success($message);
        
        // 返回json
        return $this->success($data);
        
        // 指定返回的http状态码
        return $this->setStatusCode(10000)->success($message);
        
        // 指定返回json结构 和 headers
        return $this->setStatusCode(10000)->respond([
            'code' => 10000,
            'message' => 'success',
            'data' => []
        ], $headers);
        
        return $this->message('message');
        
        return $this->failed("request faild");
        
        return $this->notLogin();
        
        return $this->notFound();
        
        return $this->internalError();
        
        return $this->created();
    }

}

```

