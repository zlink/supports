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
        return $this->success("request success");
    }

}

```

