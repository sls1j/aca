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

	//using (StreamReader reader = File.OpenText(@"C:\code\aca\data\caving-accident-data.js"))
	//{
	//	reader.ReadLine();
	//	string json = reader.ReadToEnd();
	//	json = '[' + json.Substring(0, json.Length - 1);
	//	var list = JsonConvert.DeserializeObject<List<Accident>>(json);
	//	accidents.InsertMany(list);		
	//}

	int nextId = accidents.Find("{}").SortByDescending(a => a.Id).First().Id + 1;

	ExcelPackage.LicenseContext = LicenseContext.NonCommercial;

	var file = new FileInfo(@"C:\code\aca\data\2019 - 2020 spreadsheet.xlsx");
	using (var package = new ExcelPackage(file))
	{
		ExcelWorksheet sheet = package.Workbook.Worksheets[0];
		Func<string, int, string> cellValue = (column, row) => sheet.Cells[$"{column}{row}"].Value?.ToString().Trim();
		
		Func<string,int,(int accuracy,DateTime date)> calculateDate = (input,year) => {
			DateTime date;
			if (DateTime.TryParseExact(input, "MMMM d, yyyy", CultureInfo.CurrentCulture,DateTimeStyles.None, out date))
			{
				return (0, date);
			}
			
			if (DateTime.TryParseExact(input, "MMMM dd", CultureInfo.CurrentCulture,DateTimeStyles.None, out date))
			{
				return (0, new DateTime(year,date.Month, date.Day));
			}
			
			if (DateTime.TryParseExact(input, "MMMM", CultureInfo.CurrentCulture,DateTimeStyles.None, out date))
			{
				return (1,new DateTime(year, date.Month, 1));
			}
			
			if (DateTime.TryParseExact(input, "MMMM yyyy", CultureInfo.CurrentCulture,DateTimeStyles.None, out date))
			{
				return (2,new DateTime(date.Year,date.Month,1));
			}
						
			if (int.TryParse(input, out year))
			{
				return (3,new DateTime(year,1,1));
			}
			
			var circaMatch = Regex.Match(input, @"circa (\d+)");
			if (circaMatch.Success)
			{
				return (3, new DateTime(int.Parse(circaMatch.Groups[1].Value),1,1));
			}
			
			throw new InvalidDataException($"Invalid date format '{input}' cannot parse");
		};

		Action<int, int, int, int, bool> insertSection = (cavingType, year, start, end, doInsert) =>
		{
			for (int i = start; i <= end; i++)
			{
				Func<string, string> v = (column) => cellValue(column, i);
				var incidentDate = calculateDate(v("B"), year);
				string cave = v("C");
				string location = v("D");
				string result = v("E");
				string incidentType = v("F");
				bool hasReport = !string.IsNullOrWhiteSpace(v("G"));
				string county = v("H");							

				var a = new Accident()
				{
					Id = nextId++,
					Cave = cave,
					CavingType = cavingType,
					Complete = 1,
					DateAccuracy = incidentDate.accuracy,
					IncidentDate = incidentDate.date,
					IncidentType = incidentType,
					Location = location,
					Pdf = null,
					ReportIssue = null,
					Results = result,
					Tag = 2
				};

				Console.WriteLine($"{a.Id} {a.CavingType} {a.DateAccuracy} {a.IncidentDate} {a.Cave} {a.Location}");
				if (doInsert)
					accidents.InsertOne(a);
			}
		};

		bool insert = true;
		//insertSection(0, 2019, 3, 25, insert);
		//insertSection(1, 2019, 31, 33, insert);
		//insertSection(2, 2019, 35, 41, insert);
		//insertSection(0, 2020, 43, 54, insert);
		//insertSection(1, 2020, 57, 57, insert);
		//insertSection(2, 2020, 61, 65, insert);
		
		insertSection(0,2020,68,102,insert);
		insertSection(1,2020,104,105,insert);
		insertSection(2,2020,108,109,insert);

	}
}

void ExtractSection(object sheet, int cavingType, int year)
{
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
