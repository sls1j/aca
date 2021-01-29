<Query Kind="Program">
  <NuGetReference>EPPlus</NuGetReference>
  <NuGetReference>MongoDB.Driver</NuGetReference>
  <NuGetReference>Newtonsoft.Json</NuGetReference>
  <Namespace>Newtonsoft.Json</Namespace>
  <Namespace>Newtonsoft.Json.Linq</Namespace>
  <Namespace>OfficeOpenXml</Namespace>
  <Namespace>MongoDB.Driver</Namespace>
  <Namespace>System.Globalization</Namespace>
</Query>

void Main()
{
	MongoClient client = new MongoClient("mongodb://192.168.5.10");
	var db = client.GetDatabase("aca");
	var accidents = db.GetCollection<Accident>("accidents");

	List<Accident> all = accidents.Find("{}").ToList();
	string json = JsonConvert.SerializeObject(all, Newtonsoft.Json.Formatting.Indented);
	string js = $"var $accidents = {json};";
	
	File.WriteAllText(@"c:\code\aca\data\caving-accident-data.js", js);
}

// You can define other methods, fields, classes and namespaces here
class Accident
{
	public int Id;
	public string Cave;
	public int CavingType;
	public DateTime IncidentDate;
	public int DateAccuracy;
	public string IncidentType;
	public string Location;
	public string Results;
	public string Pdf;
	public int Complete;
	public string ReportIssue;
	public int Tag;
}