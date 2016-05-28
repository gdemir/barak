<?php

class Test extends ApplicationModel
	
{
	public static $table_name ="test";
}


class ApplicationModel
{
	

    /**  PHP 5.3.0 ve sonrası  */
    public static function __callStatic($method, $arguments)
    {
        // Bilgi: $isim değeri büyük-küçük harfe duyarlıdır.
        echo "Duruk yöntem '$method' çağrılıyor: ";
		call_user_func("ApplicationModel::hmm");
		//call_user_func_array(array($this, $method), $arguments);
    }
	public static function hmm()
	{
		echo "evet";
	}

}



//YöntemSınama::deneBakalım('duruk bağlamda');  // PHP 5.3.0 ve sonrası

Test::asdasd();
