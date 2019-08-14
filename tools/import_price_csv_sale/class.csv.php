<?
class CsvReader
{
	private $file;
	private $delimiter; 
	private $length;
	private $handle; 
	private $csvArray; 

	public function __construct($file, $delimiter=";", $length = 8000) 
	{
		$this->file = $file;
		$this->length = $length;
		$this->delimiter = $delimiter;
		$this->FileOpen();
	} 

	public function __destruct()
	{
		$this->FileClose();
	}
	public function GetCsv()
	{
		$this->SetCsv();
		if(is_array($this->csvArray)) 
			return $this->csvArray;
	}
	
	private function SetCsv()
	{
		if($this->GetSize())
		{
			while (($data = @fgetcsv($this->handle, $this->length, $this->delimiter)) !== FALSE)
			{
				$this->csvArray[] = $data;
			}
		}
	}
	
	private function FileOpen()
	{
		$this->handle=($this->IsFile())?fopen($this->file, 'r'):null;
	}
	
	private function FileClose()
	{
		if($this->handle) 
			@fclose($this->handle); 
	}
	
	private function GetSize()
	{
		if($this->IsFile())
			return (filesize($this->file));
		else
			return false;
	}
	
	private function IsFile()
	{
		if(is_file($this->file) && file_exists($this->file))
			return true;
		else
			return false;
	}
} 

class CsvWriter
{
	private $file;
	private $delimiter;
	private $array;
	private $handle;
	public $error;
	
	public function __construct($file, $array, $delimiter=";")
	{
		$this->file = $file; 
		$this->array = $array; 
		$this->delimiter = $delimiter;
		$this->error = "1";
		$this->FileOpen();
	}
	
	public function __destruct()
	{
		$this->FileClose();
	}
	
	public function SaveCsv()
	{
		return $this->SetCsv();
	}
	
	private function IsWritable()
	{
		if(is_writable($this->file))
			return true;
		else
			return false;
	}
	
	private function SetCsv()
	{
		if(!is_array($this->array))
		{
			$this->error = "Data must be an array";
			return false;
		}
		
		if(!$this->IsWritable())
		{
			$this->error = "File is not writable";
			return false;
		}
		
		$content = "";
		
		foreach($this->array as $ar)
		{
			$content .= implode($this->delimiter, $ar);
			$content .= "\r\n";
		}
		
		if(fwrite($this->handle, $content) === FALSE)
		{
			$this->error = "Can'n write to file";
			return false;
		}
		
		return true;
		
	}
	
	private function FileOpen()
	{
		$this->handle = fopen($this->file, 'w+');
	}
	
	private function FileClose()
	{
		if($this->handle) 
			@fclose($this->handle); 
	} 
}
?>