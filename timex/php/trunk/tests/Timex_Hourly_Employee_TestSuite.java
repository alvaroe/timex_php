import junit.framework.Test;
import junit.framework.TestSuite;

public class Timex_Hourly_Employee_TestSuite {

  public static Test suite() {
    TestSuite suite = new TestSuite();
    suite.addTestSuite(Login_Hourly.class);
    suite.addTestSuite(Paid_Timesheets.class);
    suite.addTestSuite(Pending_Timesheet.class);
    suite.addTestSuite(Print_Timesheet.class);
    suite.addTestSuite(New_Timesheet.class);
    suite.addTestSuite(Logout.class);
    return suite;
  }

  public static void main(String[] args) {
    junit.textui.TestRunner.run(suite());
  }
}
