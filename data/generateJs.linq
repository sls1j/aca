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

}

// You can define other methods, fields, classes and namespaces here
