

```mermaid
stateDiagram-v2
    classDef publicUserState fill:#0B6E4F,stroke:#0B6E4F,color:white
    
    Draft --> Pending: submit
    Pending --> Approved: markAsApproved
    Pending --> Rejected: markAsRejected
    Draft --> Cancelled: markAsCancelled
    Pending --> Cancelled: markAsCancelled
```
