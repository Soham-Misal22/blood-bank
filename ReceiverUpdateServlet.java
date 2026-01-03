import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

@WebServlet("/ReceiverUpdateServlet")
public class ReceiverUpdateServlet extends HttpServlet {
    private static final long serialVersionUID = 1L;
    
    // Database connection details
    private static final String DB_URL = "jdbc:mysql://localhost:3306/blood_bank";
    private static final String DB_USER = "root";
    private static final String DB_PASSWORD = "";

    protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        List<ReceiverData> receivers = fetchReceiverData();
        request.setAttribute("receivers", receivers);
        request.getRequestDispatcher("receiver-update.jsp").forward(request, response);
    }

    protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        String action = request.getParameter("action");
        
        if ("update".equals(action)) {
            updateReceiver(request);
        } else if ("delete".equals(action)) {
            deleteReceiver(request);
        }
        
        response.sendRedirect("ReceiverUpdateServlet");
    }

    private List<ReceiverData> fetchReceiverData() {
        List<ReceiverData> receivers = new ArrayList<>();
        
        try {
            // Load JDBC driver
            Class.forName("com.mysql.jdbc.Driver");
            
            // Establish connection
            try (Connection conn = DriverManager.getConnection(DB_URL, DB_USER, DB_PASSWORD);
                 PreparedStatement stmt = conn.prepareStatement("SELECT * FROM receiver");
                 ResultSet rs = stmt.executeQuery()) {
                
                while (rs.next()) {
                    ReceiverData receiver = new ReceiverData(
                        rs.getInt("id"),
                        rs.getString("name"),
                        rs.getString("email"),
                        rs.getString("contact"),
                        rs.getString("dob"),
                        rs.getString("gender"),
                        rs.getString("blood_group"),
                        rs.getString("urgency"),
                        rs.getString("hospital"),
                        rs.getString("additional_info")
                    );
                    receivers.add(receiver);
                }
            }
        } catch (ClassNotFoundException | SQLException e) {
            e.printStackTrace();
        }
        
        return receivers;
    }

    private void updateReceiver(HttpServletRequest request) {
        try {
            Class.forName("com.mysql.jdbc.Driver");
            
            try (Connection conn = DriverManager.getConnection(DB_URL, DB_USER, DB_PASSWORD)) {
                String sql = "UPDATE receiver SET name=?, email=?, contact=?, dob=?, gender=?, " +
                             "blood_group=?, urgency=?, hospital=?, additional_info=? WHERE id=?";
                
                try (PreparedStatement stmt = conn.prepareStatement(sql)) {
                    int id = Integer.parseInt(request.getParameter("id"));
                    stmt.setString(1, request.getParameter("name"));
                    stmt.setString(2, request.getParameter("email"));
                    stmt.setString(3, request.getParameter("contact"));
                    stmt.setString(4, request.getParameter("dob"));
                    stmt.setString(5, request.getParameter("gender"));
                    stmt.setString(6, request.getParameter("blood_group"));
                    stmt.setString(7, request.getParameter("urgency"));
                    stmt.setString(8, request.getParameter("hospital"));
                    stmt.setString(9, request.getParameter("additional_info"));
                    stmt.setInt(10, id);
                    
                    stmt.executeUpdate();
                }
            }
        } catch (ClassNotFoundException | SQLException e) {
            e.printStackTrace();
        }
    }

    private void deleteReceiver(HttpServletRequest request) {
        try {
            Class.forName("com.mysql.jdbc.Driver");
            
            try (Connection conn = DriverManager.getConnection(DB_URL, DB_USER, DB_PASSWORD)) {
                String sql = "DELETE FROM receiver WHERE id=?";
                
                try (PreparedStatement stmt = conn.prepareStatement(sql)) {
                    int id = Integer.parseInt(request.getParameter("id"));
                    stmt.setInt(1, id);
                    
                    stmt.executeUpdate();
                }
            }
        } catch (ClassNotFoundException | SQLException e) {
            e.printStackTrace();
        }
    }

    // Inner class to represent Receiver data
    public static class ReceiverData {
        private int id;
        private String name;
        private String email;
        private String contact;
        private String dob;
        private String gender;
        private String bloodGroup;
        private String urgency;
        private String hospital;
        private String additionalInfo;

        public ReceiverData(int id, String name, String email, String contact, String dob, 
                            String gender, String bloodGroup, String urgency, 
                            String hospital, String additionalInfo) {
            this.id = id;
            this.name = name;
            this.email = email;
            this.contact = contact;
            this.dob = dob;
            this.gender = gender;
            this.bloodGroup = bloodGroup;
            this.urgency = urgency;
            this.hospital = hospital;
            this.additionalInfo = additionalInfo;
        }

        // Getters for all fields
        public int getId() { return id; }
        public String getName() { return name; }
        public String getEmail() { return email; }
        public String getContact() { return contact; }
        public String getDob() { return dob; }
        public String getGender() { return gender; }
        public String getBloodGroup() { return bloodGroup; }
        public String getUrgency() { return urgency; }
        public String getHospital() { return hospital; }
        public String getAdditionalInfo() { return additionalInfo; }
    }
}