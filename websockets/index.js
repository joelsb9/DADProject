const httpServer = require("http").createServer();
const io = require("socket.io")(httpServer, {
  cors: {
    origin: "http://127.0.0.1:5173",
    methods: ["GET", "POST"],
    credentials: true,
  },
});
httpServer.listen(8080, () => {
  console.log("listening on *:8080");
});

io.on("connection", (socket) => {
  console.log(`client ${socket.id} has connected`);

  socket.on("echo", (message) => {
    socket.emit("echo", message);
  });

  socket.on("insertedTransaction", (phonenumber) => {
    socket.emit(phonenumber, "You've transfered X euros"); //X sendo a variavel que envio
  });

  /*socket.on("loggedIn", (user) => {
    console.log(`client ${socket.id} logged in as ${user.name}`);
  });*/
});

/*if (io.sockets.connected[a_socket_id] != undefined) {
  console.log(io.sockets.connected[a_socket_id].connected); //or disconected property
} else {
  console.log("Socket not connected");
}*/